<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.applicant.applicant_profile', compact('user', 'notification', 'unreadNotifications'));
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
    public function update(Request $request)
    {
        $validateInformation = $request->validate([
            'first_name' => 'string|max:255|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'string|email|max:255',
            'phone_number' => 'string|max:11',
            'date_of_birth' => 'date|before_or_equal:today',
            'address' => 'string|max:255',
            'type_of_disability' => 'string|max:255',
            'pwd_id' => 'string|max:12|regex:/^\d{3}-\d{3}-\d{3}$/',
            'upload_pwd_card' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_picture' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($request->hasFile('upload_pwd_card')) {
            $file = $request->file('upload_pwd_card');
            $filepath = $file->store('upload_pwd_card', 'public');
            $validateInformation['upload_pwd_card'] = $filepath;
        }
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filepath = $file->store('profile_picture', 'public');
            $validateInformation['profile_picture'] = $filepath;
        }

        try {
            $user = Auth::guard('applicant')->user();
            if (!$user instanceof \App\Models\User) {
                $user = \App\Models\User::find($user->id);
            }
            foreach ($validateInformation as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
            return redirect()->route('applicant-profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the profile: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
