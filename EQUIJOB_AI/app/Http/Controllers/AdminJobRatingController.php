<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use App\Models\JobApplication;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminJobRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sortColumn = request('sort', 'created_at');
        $sortDirection = request('direction', 'desc');

        $allowedSortColumns = ['rating', 'created_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'created_at';
        }

        $admin = Auth::guard('admin')->user();
        $query = Feedbacks::query()

            ->with([
                'jobApplication.jobPosting', 'applicant'
            ])
            ->where('feedbackType', 'Job Rating')
            ->whereHas('jobApplication.jobPosting');

        $query->orderBy($sortColumn, $sortDirection);
        $feedbacks = $query->paginate(10)->withQueryString();
        $notifications = $admin->notifications;
        $unreadNotifications = $admin->unreadNotifications;

        return response()
            ->view('users.admin.feedback-job', compact(
                'admin',
                'feedbacks',
                'notifications',
                'unreadNotifications'
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
