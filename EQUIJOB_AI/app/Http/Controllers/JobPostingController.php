<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class JobPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('job_provider')->user();
        $postings = JobPosting::all(); 
        $response = response()->view('users.job-provider.job_posting', compact('user', 'postings'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'sex' => 'required|string|max:10',
            'age' => 'required|integer|min:18|max:65',
            'disability_type' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'job_posting_objectives' => 'nullable|string|max:1000',
            'requirements' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'experience' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:1000',
            'contact_phone' => 'nullable|string|max:15',
            'contact_email' => 'nullable|email|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Map job_description from form to description for saving
        if ($request->has('job_description')) {
            $validatedData['description'] = $request->input('job_description');
        }

        if($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filepath = $file->store('company_logos', 'public');
            $validatedData['company_logo'] = $filepath;
        }
        $validatedData['job_provider_id'] = Auth::guard('job_provider')->id();
        $validatedData['status'] = 'Pending'; 
        try{
            JobPosting::create($validatedData);
            return redirect()->route('job-provider-job-posting')->with('Success', 'Job posting created successfully!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the job posting: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $jobPosting->delete();
        return redirect()->back()->with('Delete_Success', 'Job posting deleted successfully');
    }
}
