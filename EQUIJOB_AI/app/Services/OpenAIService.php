<?php

namespace App.Services;

use App.Exceptions.CertificateNameMismatchException;
use Illuminate.Http.UploadedFile;
use Illuminate.Support.Facades.Log;
use OpenAI;

class OpenAIService
{
    /**
     * Extracts certificate data using OpenAI's GPT-4o model and validates the recipient's name.
     * This is a reliable, industry-standard implementation.
     *
     * @param UploadedFile $file The certificate file (image or PDF).
     * @param string $applicantFirstName The expected first name of the certificate holder.
     * @param string $applicantLastName The expected last name of the certificate holder.
     * @return array|null The extracted data on success, null on general API failure.
     * @throws CertificateNameMismatchException If the name on the certificate does not match the applicant's name.
     */
    public function extractCertificateDataFromFile(
        UploadedFile $file,
        string $applicantFirstName,
        string $applicantLastName
    ): ?array {
        Log::info('AiVisionService: Starting OpenAI API call for certificate: ' . $file->getClientOriginalName());

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            Log::error('FATAL: OPENAI_API_KEY is not configured.');
            return null;
        }

        try {
            $client = OpenAI::client($apiKey);
            $applicantFullName = "{$applicantFirstName} {$applicantLastName}";


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
                "Your final instruction: Do not reject a certificate for a single missing initial if the first and last names are a perfect match. Use your judgment to approve valid certificates with minor, common discrepancies.";


            $base64Image = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();

            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$base64Image}"
                                ]
                            ],
                        ],
                    ],
                ],
                'max_tokens' => 1000,
            ]);

            $responseText = $response->choices[0]->message->content ?? '';
            if (empty($responseText)) {
                Log::error('OpenAI Service: Received an empty response from the API.');
                return null;
            }

            $jsonResponse = $this->cleanJsonString($responseText);
            $decodedJson = json_decode($jsonResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('OpenAI Service: JSON Decode Error on certificate: ' . json_last_error_msg() . ' | Raw Text: ' . $responseText);
                return null;
            }

            // The validation logic in PHP remains the same, as it now relies entirely on the AI's improved judgment.
            $nameMatches = $decodedJson['name_match'] ?? false;

            if ($nameMatches === true) {
                Log::info('OpenAI Service: Successfully extracted and validated certificate data.', $decodedJson);
                return $decodedJson;
            } else {
                $recipientName = $decodedJson['recipient_name'] ?? 'Not Found';
                $errorMessage = "The name on the certificate ('{$recipientName}') was determined by AI to not match the applicant's name ('{$applicantFullName}').";
                
                Log::warning('OpenAI Service: Certificate name mismatch detected by AI.', [
                    'applicant_name' => $applicantFullName,
                    'certificate_name' => $recipientName,
                    'ai_response' => $decodedJson
                ]);

                throw new CertificateNameMismatchException($errorMessage);
            }

        } catch (CertificateNameMismatchException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('OpenAI Service API Exception: ' . $e->getMessage());
            return null;
        }
    }

    private function cleanJsonString(string $string): string
    {
        if (str_starts_with($string, '```json')) {
            $string = str_replace(['```json', '```'], '', $string);
        }
        return trim($string);
    }
}