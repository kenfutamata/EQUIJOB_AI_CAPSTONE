<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantJobCollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('applicant')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $search = $request->input('search');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $category = $request->input('category');
        $query = JobPosting::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                    ->orWhere('companyName', 'like', "%{$search}%")
                    ->orWhere('age', 'like', "%{$search}%")
                    ->orWhere('disabilityType', 'like', "%{$search}%")
                    ->orWhere('educationalAttainment', 'like', "%{$search}%")
                    ->orWhere('experience', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('requirements', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('contactPhone', 'like', "%{$search}%")
                    ->orWhere('contactEmail', 'like', "%{$search}%")
                    ->orWhere('companyAddress', 'like', "%{$search}%");
            });
        }
        if($category){
            $query->where('category', $category);
        }
        if ($fromDate) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        
        $sort = in_array($request->sort, ['position', 'companyName', 'age', 'disabilityType', 'educationalAttainment', 'experience', 'skills', 'requirements']) ? $request->sort : 'created_at';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);
        $collections = $query->paginate(12);
        $response = response()->view('users.applicant.applicant_job_collections', compact('user', 'notification', 'unreadNotifications', 'collections'));
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
