<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Extracts structured information by sending the PDF file directly to Gemini
     * using a manual HTTP request to bypass library caching issues.
     *
     * @param string $filePath The path to the resume file.
     * @param string $mimeType The MIME type of the file (e.g., 'application/pdf').
     * @return array|null
     */
    public function extractInformationFromResumeFile(string $filePath, string $mimeType): ?array
    {
        $apiKey = config('gemini.api_key');
        if (!$apiKey) {
            Log::error('FATAL: GEMINI_API_KEY is not configured.');
            return null;
        }

        // The official endpoint for the Gemini 1.5 Flash model
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";

        $textPrompt = "You are an expert HR data entry specialist. Analyze the attached resume file and extract the information. You MUST return the information ONLY as a valid JSON object. The JSON object must have this exact structure: {\"skills\": \"<comma-separated skills>\", \"experience_summary\": \"<summary>\", \"disability_type\": \"<type>\", \"experience_details\": [{\"job_title\": \"<title>\", \"employer\": \"<employer>\", \"year\": \"<year>\", \"description\": \"<desc>\", \"location\": \"<loc>\"}], \"education_details\": [{\"degree\": \"<degree>\", \"school\": \"<school>\", \"year\": \"<year>\", \"description\": \"<desc>\", \"location\": \"<loc>\"}]}";

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
            
            // Extract the text from the successful response
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
        if ($potentialJobs->isEmpty()) { return []; }
        $apiKey = config('gemini.api_key');
        $highestDegree = !empty($resumeData['education_details'][0]['degree']) ? $resumeData['education_details'][0]['degree'] : 'Not specified';
        $userProfileString = "## Candidate Profile:\n- Skills: " . ($resumeData['skills'] ?? 'N/A') . "\n- Experience Summary: " . ($resumeData['experience_summary'] ?? 'N/A') . "\n- Highest Education: " . $highestDegree . "\n- Disability: " . ($resumeData['disability_type'] ?? 'N/A') . "\n";
        $jobListingsString = "## Job Listings to Evaluate:\n";
        foreach ($potentialJobs as $job) { $jobListingsString .= "### Job ID: {$job->id}\n- Position: {$job->position}\n- Required Skills: " . ($job->skills ?? 'N/A') . "\n- Required Experience: " . ($job->experience ?? 'N/A') . "\n- Required Education: " . ($job->educational_attainment ?? 'N/A') . "\n- Disability Consideration: " . ($job->disability_type ?? 'N/A') . "\n\n"; }
        $prompt = "You are an expert HR recruiter... Return ONLY the JSON array. For example: [45, 12, 3]\n\n---\n{$userProfileString}---\n{$jobListingsString}---";
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";
        try {
            $response = Http::post($url, ['contents' => [['parts' => [['text' => $prompt]]]]]);
            if ($response->failed()) {
                Log::error('Gemini Direct Job Match API Failed: ' . $response->body());
                return [];
            }
            $responseText = $response->json('candidates.0.content.parts.0.text', '');
            $jsonResponse = $this->cleanJsonString($responseText);
            $rankedIds = json_decode($jsonResponse, true);
            if (is_array($rankedIds)) { return array_filter($rankedIds, 'is_numeric'); }
            return [];
        } catch (\Exception $e) {
            Log::error('Gemini Direct Job Match Exception: ' . $e->getMessage());
            return [];
        }
    }
    
    private function cleanJsonString(string $string): string
    {
        if (str_starts_with($string, '```json')) { $string = str_replace(['```json', '```'], '', $string); }
        return trim($string);
    }
}