<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; 
use Gemini;

class ResumeController extends Controller
{
    /**
     * A centralized, sanitized list of allowed disability types.
     * This is the single source of truth.
     */
    private function getDisabilityTypes(): array
    {
        return [
            'Deaf or Hard of Hearing',
            'Intellectual Disability',
            'Learning Disability',
            'Mental Disability',
            'Physical Disability (Orthopedic)',
            'Psychosocial Disability',
            'Speech and Language Impairment',
            'Visual Disability',
            'Cancer (RA11215)',
            'Rare Disease (RA10747)',
        ];
    }

    // REPLACE your existing index() method with this one
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $disabilityTypes = $this->getDisabilityTypes();

        $response = response()->view('users.applicant.resume_builder', compact('user', 'notifications', 'unreadNotifications', 'disabilityTypes'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    protected function buildResumePrompt(Resume $resume, string $skillsSummary = ''): string
    {
        $prompt = "You are a helpful assistant that refines and generates professional resume content based on provided information. Focus on clarity, conciseness, and impact.\n\n";
        $prompt .= "Create a professional and impactful resume for the candidate with the following details:\n\n";

        $prompt .= "Personal Information:\n";
        $prompt .= "Name: {$resume->firstName} {$resume->lastName}\n";
        if ($resume->dob) {
            $prompt .= "Date of Birth: " . \Carbon\Carbon::parse($resume->dob)->format('Y-m-d') . "\n";
        }
        if ($resume->address) {
            $prompt .= "Address: {$resume->address}\n";
        }
        $prompt .= "Email: {$resume->email}\n";
        if ($resume->phone) {
            $prompt .= "Phone: {$resume->phone}\n";
        }
        if (!empty($resume->typeOfDisability) && strcasecmp(trim($resume->typeOfDisability), "Select Disability Type") !== 0) {
            $prompt .= "Disability Type: {$resume->typeOfDisability}\n";
        }
        if ($resume->summary) {
            $prompt .= "Summary/Objectives (User Provided): {$resume->summary}\n";
        }
        $prompt .= "\n";

        if (!empty($skillsSummary)) {
            $prompt .= "Skills:\n{$skillsSummary}\n\n";
        }

        $resume->loadMissing(['experiences', 'educations']);

        if ($resume->experiences->isNotEmpty()) {
            $prompt .= "Work Experience:\n";
            foreach ($resume->experiences as $exp) {
                $prompt .= "- Job Title: {$exp->jobTitle}\n";
                $prompt .= "  Employer: {$exp->employer}\n";
                if ($exp->location) {
                    $prompt .= "  Location: {$exp->location}\n";
                }
                if ($exp->year) {
                    $prompt .= "  Year: {$exp->year}\n";
                }
                if ($exp->description) {
                    $prompt .= "  Responsibilities (User Provided): {$exp->description}\n";
                }
                $prompt .= "\n";
            }
        }

        if ($resume->educations->isNotEmpty()) {
            $prompt .= "Education:\n";
            foreach ($resume->educations as $edu) {
                $prompt .= "- Degree: {$edu->degree}\n";
                $prompt .= "  School: {$edu->school}\n";
                if ($edu->location) {
                    $prompt .= "  Location: {$edu->location}\n";
                }
                if ($edu->year) {
                    $prompt .= "  Year Graduated: " . \Carbon\Carbon::parse($edu->year)->format('Y-m-d') . "\n";
                }
                if ($edu->description) {
                    $prompt .= "  Description: {$edu->description}\n";
                }
                $prompt .= "\n";
            }
        }

        $prompt .= "Instructions for AI:\n";
        $prompt .= "1. Generate a professional resume summary based on all the information provided.\n";
        $prompt .= "2. Rephrase the 'Responsibilities (User Provided)' for each work experience into concise, achievement-oriented bullet points. Focus on quantifiable results where possible.\n";
        $prompt .= "3. Do not simply repeat the user-provided summary or responsibilities. Enhance and rephrase them professionally.\n";
        $prompt .= "4. Output the generated summary first, then the rephrased work experiences. Clearly label each section.\n";
        $prompt .= "Example output structure:\n";
        $prompt .= "AI Generated Summary:\n[Your generated summary here]\n\n";
        $prompt .= "AI Enhanced Work Experience:\n";
        $prompt .= "Job Title: [Job Title]\nEmployer: [Employer]\n  - [Achievement-oriented bullet point 1]\n  - [Achievement-oriented bullet point 2]\n\n";
        $prompt .= "(Repeat for each experience)\n";

        return $prompt;
    }

    public function store(Request $request, SupabaseStorageService $supabase)
    {
        $applicantUser = Auth::guard('applicant')->user();

        $validatedData = $request->validate([
            'resume.firstName' => 'required|string|max:255',
            'resume.lastName' => 'required|string|max:255',
            'resume.dob' => 'required|date',
            'resume.address' => 'required|string|max:255',
            'resume.email' => 'required|email|unique:users,email,' . $applicantUser->id,
            'resume.phone' => 'required|string|max:15',
            'resume.typeOfDisability' => ['nullable', 'string', Rule::in($this->getDisabilityTypes())],
            'resume.photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resume.summary' => 'required|string',
            'resume.skills' => 'nullable|string|max:1000',
            'experience' => 'nullable|array',
            'experience.*.employer' => 'nullable|string|max:255|required_with:experience.*.jobTitle',
            'experience.*.jobTitle' => 'nullable|string|max:255|required_with:experience.*.employer',
            'experience.*.location' => 'nullable|string|max:255',
            'experience.*.year' => 'nullable|string|max:255',
            'experience.*.responsibilities' => 'nullable|string',
            'educations' => 'nullable|array',
            'educations.*.school' => 'nullable|string|max:255|required_with:educations.*.degree',
            'educations.*.degree' => 'nullable|string|max:255|required_with:educations.*.school',
            'educations.*.location' => 'nullable|string|max:255',
            'educations.*.year' => 'nullable|string|max:255',
            'educations.*.description' => 'nullable|string',
        ]);

        try {
            $resumeInputData = $validatedData['resume'];
            // This ensures that if the field is not present, it is set to null
            if (!isset($resumeInputData['typeOfDisability'])) {
                $resumeInputData['typeOfDisability'] = null;
            }

            $skillsRaw = $request->input('skills') ?? '';
            $resumeInputData['skills'] = $skillsRaw;

            if ($request->hasFile('resume.photo')) {
                $photoPath = $supabase->upload($request->file('resume.photo'), 'photo');
                $resumeInputData['photo'] = $photoPath;
            }
            $resumeInstance = Resume::updateOrCreate(['userID' => $applicantUser->id], $resumeInputData);

            $resumeInstance->experiences()->delete();
            if (!empty($validatedData['experience'])) {
                foreach ($validatedData['experience'] as $exp) {
                    if (!empty($exp['employer']) && !empty($exp['jobTitle'])) {
                        $resumeInstance->experiences()->create([
                            'employer' => $exp['employer'],
                            'jobTitle' => $exp['jobTitle'],
                            'location' => $exp['location'] ?? null,
                            'year' => $exp['year'] ?? null,
                            'description' => $exp['responsibilities'] ?? null,
                        ]);
                    }
                }
            }
            $resumeInstance->educations()->delete();
            if (!empty($validatedData['educations'])) {
                foreach ($validatedData['educations'] as $edu) {
                    if (!empty($edu['school']) && !empty($edu['degree'])) {
                        $resumeInstance->educations()->create($edu);
                    }
                }
            }

            $promptForAI = $this->buildResumePrompt($resumeInstance, $skillsRaw);
            $geminiApiKey = config('services.gemini.api_key');
            if (!$geminiApiKey) {
                Log::error('Google Gemini API Key is not set.');
                return redirect()->back()->with('error', 'Google Gemini API Key is not configured.');
            }
            $client = Gemini::client($geminiApiKey);
            $modelName = config('services.gemini.model');
            if (!$modelName) {
                Log::error("Gemini model name not set.");
                return redirect()->back()->with('error', 'AI model name is not configured.');
            }

            $generatedContent = null;
            $maxRetries = 3;
            $initialDelay = 1;

            for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                try {
                    $response = $client->generativeModel($modelName)->generateContent($promptForAI);
                    $generatedContent = $response->text();
                    if (!empty($generatedContent)) {
                        break;
                    }
                } catch (\Exception $e) {
                    Log::warning("Gemini API call failed on attempt " . ($attempt + 1) . ". Error: " . $e->getMessage());
                    if ($attempt === $maxRetries - 1) {
                        throw $e;
                    }
                    $delay = $initialDelay * (2 ** $attempt);
                    sleep($delay);
                }
            }

            if (empty($generatedContent)) {
                $generatedContent = 'AI could not generate additional content for the resume.';
            }

            $resumeInstance->aiGeneratedSummary = $generatedContent;
            $resumeInstance->save();

            session()->flash('generated_resume_content', $generatedContent);
            session()->flash('skills_summary_for_download', $skillsRaw);

            return redirect()->route('applicant-resume-view-and-download')->with('success', 'Resume processed successfully with AI enhancements!');
        } catch (\Throwable $e) {
            Log::error('Error in ResumeController@store: ' . $e->getMessage());
            if (str_contains(strtolower($e->getMessage()), 'overloaded')) {
                $errorMessage = 'The AI assistant is currently experiencing high demand and could not process your request. Your resume has been saved. Please try again in a few moments.';
            } else {
                $errorMessage = 'An unexpected error occurred during AI processing: ' . $e->getMessage();
            }
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function viewAndDownload()
    {
        $user = Auth::guard('applicant')->user();
        $user->load('resume.experiences', 'resume.educations');
        $resume = $user->resume;
        if (!$resume) {
            return redirect()->route('applicant.resume.builder')->with('error', 'No resume data found. Please build your resume first.');
        }
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $generatedSummary = session('generated_resume_content', $resume->aiGeneratedSummary ?? 'No AI summary generated yet.');
        $skillsRaw = session('skills_summary_for_download', $resume->skills ?? '');
        $skillsList = !empty($skillsRaw) ? array_map('trim', explode(',', $skillsRaw)) : [];
        return view('users.applicant.resume_view_download', [
            'user' => $user,
            'resume' => $resume,
            'generatedSummary' => $generatedSummary,
            'skillsList' => $skillsList,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    // Unchanged methods
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
