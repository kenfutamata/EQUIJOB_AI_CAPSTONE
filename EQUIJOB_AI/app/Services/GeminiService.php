<?php

namespace App\Services;

use Gemini;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Extracts structured certificate data from a file using a direct API call.
     */
    public function extractCertificateDataFromFile(UploadedFile $file): ?array
    {
        Log::info('GeminiService: Starting direct HTTP call for certificate file: ' . $file->getClientOriginalName());

        $apiKey = config('gemini.api_key');
        if (!$apiKey) {
            Log::error('FATAL: GEMINI_API_KEY is not configured.');
            return null;
        }

        // Using the model confirmed to be working for this multimodal task.
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $prompt = "Analyze this certificate image/PDF. Extract the primary skill or certification name, the name of the issuing organization, and the date of issue. Respond ONLY with a valid JSON object in the following format: {\"skill_name\": \"...\", \"issuer\": \"...\", \"issue_date\": \"...\"}. If a field cannot be found, use null for its value.";

        $fileData = [
            'inline_data' => [
                'mime_type' => $file->getMimeType(),
                'data'      => base64_encode(file_get_contents($file->getRealPath())),
            ],
        ];

        $requestBody = [
            'contents' => [
                'parts' => [
                    ['text' => $prompt],
                    $fileData,
                ],
            ],
        ];

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(120) // Keep the timeout for network stability
                ->post($url, $requestBody);

            if ($response->failed()) {
                Log::error('Gemini Direct Certificate API Call Failed: ' . $response->body());
                return null;
            }

            $responseText = $response->json('candidates.0.content.parts.0.text', '');
            $jsonResponse = $this->cleanJsonString($responseText);
            $decodedJson = json_decode($jsonResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('GeminiService: JSON Decode Error on certificate: ' . json_last_error_msg() . ' | Raw Text: ' . $responseText);
                return null;
            }

            Log::info('GeminiService: Successfully extracted certificate data via direct HTTP call.', $decodedJson);
            return $decodedJson;
        } catch (\Exception $e) {
            Log::error('Gemini Direct Certificate API Exception: ' . $e->getMessage());
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


    /**
     * Helper to clean the JSON string returned by the API.
     */
    private function cleanJsonString(string $string): string
    {
        if (str_starts_with($string, '```json')) {
            $string = str_replace(['```json', '```'], '', $string);
        }
        return trim($string);
    }
}
