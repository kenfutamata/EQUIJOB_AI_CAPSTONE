<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $feedbacks = Feedbacks::with('jobApplication.jobPosting')
            ->where('applicantID', $user->id)
            ->where('feedbackType', 'Job Rating')
            ->paginate(10);
        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();
        return response()
            ->view('users.applicant.applicant-feedback', compact(
                'user',
                'feedbacks',
                'notifications',
                'unreadNotifications',
            ))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
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
        $request->validate([
            'feedbackText' => 'required|string|max:500',
            'rating'=> 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedbacks::findOrFail($id);
        $feedback->feedbackText = $request->input('feedbackText');
        $feedback->status = 'Completed';
        $feedback->save();

        return redirect()->back()->with('success', 'Feedback submitted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
