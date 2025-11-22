<?php

namespace App\Services;

// IMPORTANT: Make sure these 'use' statements are at the top of your file
use App\Exceptions\CertificateNameMismatchException;
use Google\Auth\ApplicationDefaultCredentials;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Extracts certificate data using the official Vertex AI API and validates the recipient's name.
     * This is the correct production-ready implementation that uses service account authentication.
     */
    public function extractCertificateDataFromFile(
        UploadedFile $file,
        string $applicantFirstName,
        string $applicantLastName
    ): ?array {
        Log::info('GeminiService: Starting final Vertex AI call for certificate file: ' . $file->getClientOriginalName());

        $projectId = config('services.gemini.project_id');
        $location = config('services.gemini.location');

        if (!$projectId || !$location) {
            Log::error('FATAL: GEMINI_PROJECT_ID or GEMINI_LOCATION is not configured.');
            return null;
        }

        // =========================================================================================
        // === FINAL FIX 1: Switched to the universally available 'gemini-1.0-pro-vision' model. ===
        // =========================================================================================
        $model = 'gemini-1.0-pro-vision';

        // ===============================================================================================
        // === FINAL FIX 2: Switched to the standard ':generateContent' endpoint for single requests. ===
        // ===============================================================================================
        $url = "https://{$location}-aiplatform.googleapis.com/v1/projects/{$projectId}/locations/{$location}/publishers/google/models/{$model}:generateContent";

        $applicantFullName = "{$applicantFirstName} {$applicantLastName}";
        $prompt = "Analyze this certificate image/PDF for a person named '{$applicantFullName}'. " .
            "Extract the primary skill or certification name, the name of the issuing organization, the date of issue, and the full name of the person who received the certificate. " .
            "Respond ONLY with a valid JSON object in the following format: " .
            "{\"skill_name\": \"...\", \"issuer\": \"...\", \"issue_date\": \"...\", \"recipient_name\": \"...\"}. " .
            "If a field cannot be found, use null for its value.";

        $requestBody = [
            'contents' => [
                'parts' => [
                    ['text' => $prompt],
                    [
                        'inline_data' => [
                            'mime_type' => $file->getMimeType(),
                            'data'      => base64_encode(file_get_contents($file->getRealPath())),
                        ],
                    ]
                ],
            ],
        ];

        try {
            // Authentication is correct and remains the same
            $scopes = ['https://www.googleapis.com/auth/cloud-platform'];
            $auth = ApplicationDefaultCredentials::getCredentials($scopes);

            $response = \Illuminate\Support\Facades\Http::withToken(
                collect($auth->fetchAuthToken())->get('access_token')
            )
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(120)
                ->post($url, $requestBody);

            if ($response->failed()) {
                Log::error('Gemini Vertex AI API Call Failed: ' . $response->body());
                return null;
            }

            // =================================================================================================
            // === FINAL FIX 3: Adjusted JSON parsing for the non-streamed ':generateContent' response. ===
            // =================================================================================================
            $responseText = $response->json('candidates.0.content.parts.0.text', '');
            if (empty($responseText)) {
                Log::error('GeminiService: Empty response text from Vertex AI.', $response->json());
                return null;
            }

            $jsonResponse = $this->cleanJsonString($responseText);
            $decodedJson = json_decode($jsonResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('GeminiService: JSON Decode Error on certificate: ' . json_last_error_msg() . ' | Raw Text: ' . $responseText);
                return null;
            }

            // Your validation logic is correct and remains unchanged
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

            Log::info('GeminiService: Successfully extracted and validated certificate data via Vertex AI.', $decodedJson);
            return $decodedJson;
        } catch (CertificateNameMismatchException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gemini Vertex AI API Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return null;
        }
    }


    /**
     * Extracts structured resume data from a file using a direct API call.
     */
    public function extractInformationFromResumeFile(string $filePath, string $mimeType): ?array
    {
        $apiKey = config('gemini.api_key');
        if (!$apiKey) {
            Log::error('FATAL: GEMINI_API_KEY is not configured.');
            return null;
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        // =================================================================
        // START: PROMPT IMPROVEMENT
        // =================================================================
        $textPrompt = "You are an expert HR data entry specialist from EQUIJOB. Analyze the attached resume file and extract the information. "
            . "You MUST return the information ONLY as a valid JSON object. "
            . "CRITICAL: If the provided file does not appear to be a professional resume or Curriculum Vitae (CV), you MUST return an empty JSON object like `{}`. "
            . "The JSON object must have this exact structure: {\"skills\": \"<comma-separated skills>\", \"experience_summary\": \"<summary>\", \"disability_type\": \"<type>\", \"experience_details\": [{\"job_title\": \"<title>\", \"employer\": \"<employer>\", \"year\": \"<year>\", \"description\": \"<desc>\", \"location\": \"<loc>\"}], \"education_details\": [{\"degree\": \"<degree>\", \"school\": \"<school>\", \"year\": \"<year>\", \"description\": \"<desc>\", \"location\": \"<loc>\"}]}";
        // =================================================================
        // END: PROMPT IMPROVEMENT
        // =================================================================

        $fileData = [
            'inline_data' => [
                'mime_type' => $mimeType,
                'data' => base64_encode(file_get_contents($filePath)),
            ],
        ];
        $requestBody = [
            'contents' => [
                'parts' => [
                    ['text' => $textPrompt],
                    $fileData,
                ],
            ],
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $requestBody);

            if ($response->failed()) {
                Log::error('Gemini Direct API Call Failed: ' . $response->body());
                return null;
            }

            $responseText = $response->json('candidates.0.content.parts.0.text', '');
            $jsonResponse = $this->cleanJsonString($responseText);

            return json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            Log::error('Gemini Direct API Exception: ' . $e->getMessage());
            return null;
        }
    }
    public function getAiJobMatches(array $resumeData, $potentialJobs): array
    {
        if ($potentialJobs->isEmpty()) {
            return [];
        }
        $apiKey = config('gemini.api_key');
        $highestDegree = !empty($resumeData['education_details'][0]['degree']) ? $resumeData['education_details'][0]['degree'] : 'Not specified';
        $userProfileString = "## Candidate Profile:\n- Skills: " . ($resumeData['skills'] ?? 'N/A') . "\n- Experience Summary: " . ($resumeData['experience_summary'] ?? 'N/A') . "\n- Highest Education: " . $highestDegree . "\n- Disability: " . ($resumeData['disability_type'] ?? 'N/A') . "\n";
        $jobListingsString = "## Job Listings to Evaluate:\n";
        foreach ($potentialJobs as $job) {
            $jobListingsString .= "### Job ID: {$job->id}\n- Position: {$job->position}\n- Required Skills: " . ($job->skills ?? 'N/A') . "\n- Required Experience: " . ($job->experience ?? 'N/A') . "\n- Required Education: " . ($job->educationalAttainment ?? 'N/A') . "\n- Disability Consideration: " . ($job->disabilityType ?? 'N/A') . "\n\n";
        }
        $prompt = "You are an expert HR recruiter... Return ONLY the JSON array. For example: [45, 12, 3]\n\n---\n{$userProfileString}---\n{$jobListingsString}---";
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";
        try {
            $response = Http::post($url, ['contents' => [['parts' => [['text' => $prompt]]]]]);
            if ($response->failed()) {
                Log::error('Gemini Direct Job Match API Failed: ' . $response->body());
                return [];
            }
            $responseText = $response->json('candidates.0.content.parts.0.text', '');
            $jsonResponse = $this->cleanJsonString($responseText);
            $rankedIds = json_decode($jsonResponse, true);
            if (is_array($rankedIds)) {
                return array_filter($rankedIds, 'is_numeric');
            }
            return [];
        } catch (\Exception $e) {
            Log::error('Gemini Direct Job Match Exception: ' . $e->getMessage());
            return [];
        }
    }

    private function cleanJsonString(string $string): string
    {
        if (str_starts_with($string, '```json')) {
            $string = str_replace(['```json', '```'], '', $string);
        }
        return trim($string);
    }


    public function __destruct()
    {
        if ($this->client) {
            $this->client->close();
        }
    }
}
