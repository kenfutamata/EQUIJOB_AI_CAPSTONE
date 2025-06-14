<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminManageJobPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $postings = JobPosting::all();
        $response = response()->view('users.admin.admin_manage_job_posting', compact('user', 'postings'));
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

        public function updateForPosting(string $id)
    {
        $JobPosting = JobPosting::findOrFail($id);
        $JobPosting->status = 'For Posting';
        $JobPosting->save();
        return redirect()->back()->with('Success', 'Job posting status updated successfully');
    }

    public function updateDisapproved(string $id)
    {
        $JobPosting = JobPosting::findOrFail($id);
        $JobPosting->status = 'Disapproved';
        $JobPosting->save();
        return redirect()->back()->with('Success', 'Job posting status Disapproved');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
