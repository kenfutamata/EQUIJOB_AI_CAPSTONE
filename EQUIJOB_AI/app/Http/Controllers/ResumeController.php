<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Gemini;

class ResumeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.applicant.resume_builder', compact('user', 'notifications', 'unreadNotifications'));
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
        $prompt .= "Name: {$resume->first_name} {$resume->last_name}\n";
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
        // Add Disability Type if present
        if (!empty($resume->type_of_disability) && strcasecmp(trim($resume->type_of_disability), "Select Disability Type") !== 0) {
            $prompt .= "Disability Type: {$resume->type_of_disability}\n";
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
                $prompt .= "- Job Title: {$exp->job_title}\n";
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

    public function store(Request $request)
    {
        $applicantUser = Auth::guard('applicant')->user();

        $validatedData = $request->validate([
            'resume.first_name' => 'required|string|max:255',
            'resume.last_name' => 'required|string|max:255',
            'resume.dob' => 'nullable|date',
            'resume.address' => 'nullable|string|max:255',
            'resume.email' => 'required|email|unique:users,email,' . $applicantUser->id,
            'resume.phone' => 'nullable|string|max:15',
            'resume.type_of_disability' => 'nullable|string|max:255',
            'resume.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resume.summary' => 'nullable|string',
            'resume.skills' => 'nullable|string|max:1000',
            'experience' => 'nullable|array',
            'experience.*.employer' => 'nullable|string|max:255|required_with:experience.*.job_title',
            'experience.*.job_title' => 'nullable|string|max:255|required_with:experience.*.employer',
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
            // Your existing data saving logic is good, we keep it as is.
            $resumeInputData = $validatedData['resume'];
            if (!isset($resumeInputData['type_of_disability'])) {
                $resumeInputData['type_of_disability'] = '';
            }
            $skillsRaw = $request->input('skills', '');
            if (!empty($skillsRaw)) {
                $resumeInputData['skills'] = $skillsRaw;
            }
            if ($request->hasFile('resume.photo')) {
                $path = $request->file('resume.photo')->store('resume_photos', 'public');
                $resumeInputData['photo'] = $path;
            }
            unset($resumeInputData['photo']);
            if (isset($path)) {
                $resumeInputData['photo'] = $path;
            }
            $resumeInstance = Resume::updateOrCreate(['user_id' => $applicantUser->id], $resumeInputData);
            $resumeInstance->experiences()->delete();
            if (!empty($validatedData['experience'])) {
                foreach ($validatedData['experience'] as $exp) {
                    if (!empty($exp['employer']) && !empty($exp['job_title'])) {
                        $resumeInstance->experiences()->create([
                            'employer' => $exp['employer'],
                            'job_title' => $exp['job_title'],
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
            $initialDelay = 1; // seconds

            for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                try {
                    $response = $client->generativeModel($modelName)->generateContent($promptForAI);
                    $generatedContent = $response->text();
                    // If successful, break out of the retry loop
                    if (!empty($generatedContent)) {
                        break;
                    }
                } catch (\Exception $e) {
                    Log::warning("Gemini API call failed on attempt " . ($attempt + 1) . ". Error: " . $e->getMessage());
                    // If this was the last attempt, re-throw the exception to be caught by the outer catch block
                    if ($attempt === $maxRetries - 1) {
                        throw $e; 
                    }
                    // Wait before retrying (exponential backoff)
                    $delay = $initialDelay * (2 ** $attempt);
                    sleep($delay);
                }
            }
            

            if (empty($generatedContent)) {
                // This will be used if the AI returns an empty response even without an error
                $generatedContent = 'AI could not generate additional content for the resume.';
            }

            $resumeInstance->ai_generated_summary = $generatedContent;
            $resumeInstance->save();

            $resumeData = $resumeInstance->load('experiences', 'educations')->toArray();
            session()->flash('generated_resume_content', $generatedContent);
            session()->flash('resume_data_for_download', $resumeData);
            session()->flash('skills_summary_for_download', $skillsRaw);

            return redirect()->route('applicant-resume-view-and-download')->with('success', 'Resume processed successfully with AI enhancements!');

        } catch (\Throwable $e) {
            Log::error('Error in ResumeController@store: ' . $e->getMessage());
            // Provide a more user-friendly message for the specific "overloaded" error
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
        $generatedContent = session('generated_resume_content');
        $resumeDataArray = session('resume_data_for_download');
        $skillsSummary = session('skills_summary_for_download');

        if (!$resumeDataArray) {
            return redirect()->route('applicant.resume.builder')->with('error', 'No resume data found. Please build your resume first.');
        }

        $resumeInstance = new Resume($resumeDataArray);

        if (isset($resumeDataArray['experiences'])) {
            $experiences = \App\Models\Experience::hydrate($resumeDataArray['experiences'])->all();
            $resumeInstance->setRelation('experiences', collect($experiences));
        } else {
            $resumeInstance->setRelation('experiences', collect());
        }

        if (isset($resumeDataArray['educations'])) {
            $educations = \App\Models\Education::hydrate($resumeDataArray['educations'])->all();
            $resumeInstance->setRelation('educations', collect($educations));
        } else {
            $resumeInstance->setRelation('educations', collect());
        }

        return view('users.applicant.resume_view_download', compact('generatedContent', 'resumeInstance', 'skillsSummary'));
    }
    
    // Unchanged methods
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}