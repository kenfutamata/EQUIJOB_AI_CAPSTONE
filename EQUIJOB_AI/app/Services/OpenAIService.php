<?php

namespace App\Services;

use App\Exceptions\CertificateNameMismatchException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenAI; // <-- Add this at the top

class OpenAIService // or AiVisionService
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
            Log::error('FATAL: OPENAI_API_KEY is not configured in your .env file.');
            return null;
        }

        try {
            // 1. Initialize the OpenAI Client
            $client = OpenAI::client($apiKey);

            // 2. The prompt is the same, as the core task hasn't changed.
            $applicantFullName = "{$applicantFirstName} {$applicantLastName}";
            $prompt = "Analyze this certificate image/PDF for a person named '{$applicantFullName}'. " .
                "Extract the primary skill or certification name, the name of the issuing organization, the date of issue, and the full name of the person who received the certificate. " .
                "Respond ONLY with a valid JSON object in the following format: " .
                "{\"skill_name\": \"...\", \"issuer\": \"...\", \"issue_date\": \"...\", \"recipient_name\": \"...\"}. " .
                "If a field cannot be found, use null for its value.";

            // 3. Prepare the image data in the format OpenAI expects
            $base64Image = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();

            // 4. Build and send the request to the GPT-4o model
            $response = $client->chat()->create([
                'model' => 'gpt-4o', // The latest, fastest, and most capable vision model
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
                'max_tokens' => 1000, // Ample tokens for a JSON response
            ]);

            // 5. Process the response
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

            // 6. Your existing validation logic remains UNCHANGED and works perfectly here.
            $recipientName = $decodedJson['recipient_name'] ?? null;
            if (empty($recipientName)) {
                throw new CertificateNameMismatchException("Could not find a name on the certificate to validate.");
            }

            $normalizedApplicantName = strtolower(trim($applicantFullName));
            $normalizedRecipientName = strtolower(trim($recipientName));
            if (strpos($normalizedRecipientName, $normalizedApplicantName) === false) {
                throw new CertificateNameMismatchException(
                    "The name on the certificate ('{$recipientName}') does not appear to match the applicant's name ('{$applicantFullName}')."
                );
            }

            Log::info('OpenAI Service: Successfully extracted and validated certificate data.', $decodedJson);
            return $decodedJson;
        } catch (CertificateNameMismatchException $e) {
            throw $e; // Re-throw for the controller to catch
        } catch (\Exception $e) {
            // The OpenAI client throws specific exceptions, but this will catch them all.
            Log::error('OpenAI Service API Exception: ' . $e->getMessage());
            return null;
        }
    }

    // ... The rest of your methods in the class ...

    private function cleanJsonString(string $string): string
    {
        // This helper function is still useful!
        if (str_starts_with($string, '```json')) {
            $string = str_replace(['```json', '```'], '', $string);
        }
        return trim($string);
    }
}
