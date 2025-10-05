<?php

namespace App\Http\Controllers;

use App\Exports\AdminSystemRatingDataExport;
use App\Models\Feedbacks;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Maatwebsite\Excel\Facades\Excel;

class AdminContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = FacadesAuth::guard('admin')->user();
        $sortColumn = request('sort', 'created_at');
        $sortDirection = request('direction', 'desc');
        $query = Feedbacks::whereIn('feedbackType', ['Job Application Issues', 'AI-Job Matching Issues', 'Resume Builder Problems', 'Other']);
        $query->orderBy($sortColumn, $sortDirection);
        $feedbacks = $query->paginate(10)->withQueryString();
        $notifications = $admin->notifications;
        $unreadNotifications = $admin->unreadNotifications;
        return response()
            ->view('users.admin.feedback-contact-us', compact(
                'admin',
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
    public function destroy(Feedbacks $feedback)
    {
        $feedback->delete();
        return redirect()->back()->with('Delete_Success', 'Feedback deleted successfully');
    }

    public function export(Request $request)
    {
        return Excel::download(new AdminSystemRatingDataExport(
            $request->search,
            $request->sort,
            $request->direction
        ),
            'EQUIJOB System Rating.xlsx'
        );
    }
}
