<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Extracts structured information from the resume.
     * This prompt is refined for better field mapping.
     */
    public function extractInformationResume(string $resumeText): ?array
    {
        // REFINED PROMPT for better parsing
        $prompt = "You are an expert HR data entry specialist. Analyze the following resume text and extract the information.
        You MUST return the information ONLY as a valid JSON object. Do not add any text, explanations, or markdown formatting before or after the JSON.
        The JSON object must have this exact structure:
        {
          \"skills\": \"<A single, comma-separated string of all technical and soft skills. Example: 'PHP, Laravel, Communication, Project Management'>\",
          \"experience_summary\": \"<A 2-3 sentence summary of the candidate's entire professional work history.>\",
          \"disability_type\": \"<Identify any mentioned disability. If none is mentioned, return 'Not Specified'.>\",
          \"experience_details\": [
            {
              \"job_title\": \"<The job title for a single position, e.g., Software Developer>\",
              \"employer\": \"<The company name, e.g., Job Street.com>\",
              \"year\": \"<The start and end year, e.g., 2019-2022>\",
              \"description\": \"<A 1-2 sentence summary of responsibilities for this specific job.>\",
              \"location\": \"<The city or location of the job, e.g., Mandaue City>\"
            }
          ],
          \"education_details\": [
            {
              \"degree\": \"<The degree name, e.g., BSIT>\",
              \"school\": \"<The university or school name, e.g., lkjljklkjjk>\",
              \"year\": \"<The graduation year, e.g., 2025>\",
              \"description\": \"<Any relevant details.>\",
              \"location\": \"<The city or location of the school, e.g., Mandaue City>\"
            }
          ]
        }
        Resume Text to Analyze:
        ---
        {$resumeText}
        ---
        ";

        try {
            $result = Gemini::generativeModel(model: 'gemini-1.5-flash-latest')->generateContent($prompt);
            $jsonResponse = $this->cleanJsonString($result->text());
            return json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            Log::error('Gemini API Parsing Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * UPGRADED METHOD: Uses AI to perform an expert-level comparison and ranking.
     */
    public function getAiJobMatches(array $resumeData, $potentialJobs): array
    {
        if ($potentialJobs->isEmpty()) {
            return [];
        }

        // 1. Create a richer candidate profile.
        $highestDegree = !empty($resumeData['education_details'][0]['degree']) ? $resumeData['education_details'][0]['degree'] : 'Not specified';
        $userProfileString = "## Candidate Profile:\n";
        $userProfileString .= "- Skills: " . ($resumeData['skills'] ?? 'Not provided') . "\n";
        $userProfileString .= "- Experience Summary: " . ($resumeData['experience_summary'] ?? 'Not provided') . "\n";
        $userProfileString .= "- Highest Education: " . $highestDegree . "\n";
        $userProfileString .= "- Disability: " . ($resumeData['disability_type'] ?? 'Not Specified') . "\n";

        // 2. Create a more detailed list of jobs for the AI to analyze.
        $jobListingsString = "## Job Listings to Evaluate:\n";
        foreach ($potentialJobs as $job) {
            $jobListingsString .= "### Job ID: {$job->id}\n";
            $jobListingsString .= "- Position: {$job->position}\n";
            $jobListingsString .= "- Required Skills: " . ($job->skills ?? 'Not specified') . "\n";
            $jobListingsString .= "- Required Experience: " . ($job->experience ?? 'Not specified') . "\n";
            $jobListingsString .= "- Required Education: " . ($job->educational_attainment ?? 'Not specified') . "\n";
            $jobListingsString .= "- Disability Consideration: " . ($job->disability_type ?? 'Not specified') . "\n\n";
        }

        // 3. THE NEW "EXPERT RECRUITER" PROMPT
        $prompt = "You are an expert HR recruiter with a talent for matching candidates to jobs.
        Your task is to analyze the Candidate Profile and compare it against each of the Job Listings.

        Evaluate each job based on how well the candidate's Skills, Experience, and Education match the job's requirements.
        - A strong skill overlap is very important.
        - The candidate's Experience Summary should align with the job's Required Experience.
        - The candidate's Highest Education should meet or exceed the job's Required Education.
        - Give special consideration if the Disability types match.

        After your analysis, return a JSON array containing the job IDs that are the best match for the candidate.
        Order the array from the MOST suitable job to the LEAST suitable. Only include good matches.
        Return ONLY the JSON array and nothing else. For example: [45, 12, 3]

        ---
        {$userProfileString}
        ---
        {$jobListingsString}
        ---
        ";

        try {
            $result = Gemini::generativeModel(model: 'gemini-1.5-flash-latest')->generateContent($prompt);
            $jsonResponse = $this->cleanJsonString($result->text());
            $rankedIds = json_decode($jsonResponse, true);

            if (is_array($rankedIds)) {
                return array_filter($rankedIds, 'is_numeric');
            }
            Log::warning('Gemini Job Match returned an invalid format: ' . $jsonResponse);
            return [];
        } catch (\Exception $e) {
            Log::error('Gemini Job Matching Error: ' . $e->getMessage());
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
}