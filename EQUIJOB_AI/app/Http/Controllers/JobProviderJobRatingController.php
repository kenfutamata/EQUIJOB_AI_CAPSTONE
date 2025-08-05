<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JobProviderJobRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('job_provider')->user();

        $query = Feedbacks::query()
        ->with(['jobApplication.jobPosting', 'applicant'])
        ->where('feedbackType', 'Job Rating')
        ->whereHas('jobPosting', function($q) use ($user){
            $q->where('jobProviderID', $user->id);
        });
        $sortColumn = request('sort_column', 'created_at');
        $sortDirection = request('sort_direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);
        $feedbacks = $query->paginate(10)->withQueryString();
        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();

        return response()
            ->view('users.job-provider.job_provider_applicant_feedback', compact(
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
