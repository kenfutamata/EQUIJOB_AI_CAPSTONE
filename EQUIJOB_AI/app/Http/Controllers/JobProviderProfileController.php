<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobProviderProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('job_provider')->user();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $response = response()->view('users.job-provider.job_provider_profile', compact('user', 'notification', 'unreadNotifications'));
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
            'first_name' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'email' => 'string|email|max:255',
            'business_permit' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'company_name' => 'string|max:100|regex:/^[A-Za-z\s]+$/',
            'company_logo'=> 'file|mimes:jpg,jpeg,png|max:2048',
            'profile_picture' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);
        if($request->hasFile('company_logo')){
            $file = $request->file('company_logo'); 
            $filepath = $file->store('company_logo', 'public'); 
            $validateInformation['company_logo'] = $filepath;
        }
        if ($request->hasFile('business_permit')) {
            $file = $request->file('business_permit');
            $filepath = $file->store('business_permit', 'public');
            $validateInformation['business_permit'] = $filepath;
        }
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filepath = $file->store('profile_picture', 'public');
            $validateInformation['profile_picture'] = $filepath;
        }

        try {
            $user = Auth::guard('job_provider')->user();
            if (!$user instanceof \App\Models\User) {
                $user = \App\Models\User::find($user->id);
            }
            foreach ($validateInformation as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
            return redirect()->back()->with('success', 'Profile updated successfully.');
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
