<?php

namespace App\Services;

use App\Exceptions\CertificateNameMismatchException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    protected Client $client;

    public function __construct()
    {
        $apiKey = config('services.openai.api_key');
        if (!$apiKey) {
            throw new \InvalidArgumentException('FATAL: OPENAI_API_KEY is not configured in your .env file or config/services.php.');
        }
        $this->client = OpenAI::client($apiKey);
    }

    /**
     * Extracts and validates certificate data with maximum reliability.
     */
    public function extractCertificateDataFromFile(
        UploadedFile $file,
        string $applicantFirstName,
        string $applicantLastName
    ): ?array {
        Log::info('AiVisionService: Starting enhanced OpenAI API call for certificate: ' . $file->getClientOriginalName());

        $applicantFullName = "{$applicantFirstName} {$applicantLastName}";

        // The full, unabbreviated prompt for clarity.
        $prompt = "You are an intelligent document verification specialist. Your primary task is to analyze this certificate and confidently verify if it was awarded to '{$applicantFullName}'. ".
                "Your goal is to correctly identify certificates belonging to the user, even with minor name variations, while strictly rejecting certificates that clearly belong to someone else. ".
                "First, extract the data. Second, make a final judgment on the name match.\n\n".
                "Respond ONLY with a valid JSON object in the following format: ".
                "{\"skill_name\": \"...\", \"issuer\": \"...\", \"issue_date\": \"...\", \"recipient_name\": \"...\", \"name_match\": boolean}.\n\n".
                "GUIDING PRINCIPLES for the `name_match` field:\n\n".
                "1.  You SHOULD set `name_match` to `true` if you have high confidence the names refer to the same person. Examples of acceptable matches include:\n".
                "    -   **Full Match:** 'John Doe' on certificate vs 'John Doe' applicant.\n".
                "    -   **Inclusion of Middle Names/Initials:** 'John Michael Doe' or 'John M. Doe' on certificate vs 'John Doe' applicant.\n".
                "    -   **Common Typos:** 'Jhon Doe' on certificate vs 'John Doe' applicant.\n".
                "    -   **Name Order Reversal:** 'Doe, John' on certificate vs 'John Doe' applicant.\n".
                "    -   **Presence of Titles:** 'Mr. John Doe' on certificate vs 'John Doe' applicant.\n\n".
                "2.  You MUST set `name_match` to `false` if the names are clearly different. Examples of unacceptable matches include:\n".
                "    -   **Different First and Last Name:** 'Jane Smith' on certificate vs 'John Doe' applicant.\n".
                "    -   **Partial but Ambiguous Match:** 'John Smith' on certificate vs 'John Doe' applicant (different last names).\n".
                "    -   **No Name Found:** If a recipient name cannot be found on the certificate.\n\n".
                "Your final instruction: Do not reject a certificate for a single missing initial if the first and last names are a perfect match. Use your judgment to approve valid certificates with minor, common discrepancies. You must respond in JSON format.";


        $base64Image = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType = $file->getMimeType();

        $maxRetries = 3;
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = $this->client->chat()->create([
                    // FIX #1: Switch to the most stable vision model for this feature set.
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => $prompt],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => "data:{$mimeType};base64,{$base64Image}",
                                        // FIX #2: Force low detail to prevent timeouts with large images.
                                        'detail' => 'low',
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'max_tokens' => 1000,
                    'response_format' => ['type' => 'json_object'],
                ]);

                $responseText = $response->choices[0]->message->content ?? '';
                if (empty($responseText)) {
                    throw new \Exception('Received an empty response from the API.');
                }

                $decodedJson = json_decode($responseText, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Failed to decode guaranteed JSON: ' . json_last_error_msg());
                }

                $nameMatches = $decodedJson['name_match'] ?? false;
                if ($nameMatches === true) {
                    Log::info('OpenAI Service: Successfully extracted and validated certificate data.', $decodedJson);
                    return $decodedJson;
                } else {
                    $recipientName = $decodedJson['recipient_name'] ?? 'Not Found';
                    $errorMessage = "The name on the certificate ('{$recipientName}') was determined by AI to not match the applicant's name ('{$applicantFullName}').";
                    throw new CertificateNameMismatchException($errorMessage);
                }

            } catch (CertificateNameMismatchException $e) {
                Log::warning('OpenAI Service: Certificate name mismatch detected by AI.', [
                    'applicant_name' => $applicantFullName,
                    'ai_response' => $decodedJson ?? ['error' => 'Could not decode AI response']
                ]);
                throw $e;
            } catch (\Exception $e) {
                Log::error("OpenAI Service API Exception (Attempt {$attempt}/{$maxRetries}): " . $e->getMessage());
                if ($attempt < $maxRetries) {
                    sleep(2);
                } else {
                    Log::error("OpenAI Service: All {$maxRetries} attempts failed.");
                    return null;
                }
            }
        }
        
        return null;
    }

    private function cleanJsonString(string $string): string
    {
        if (str_starts_with($string, '```json')) {
            $string = str_replace(['```json', '```'], '', $string);
        }
        return trim($string);
    }
}