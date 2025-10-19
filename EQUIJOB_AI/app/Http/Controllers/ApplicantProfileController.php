<?php

namespace App\Http\Controllers;

use App\Services\SupabaseStorageService;
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
    public function update(Request $request, SupabaseStorageService $supabase)
    {
        $validateInformation = $request->validate([
            'firstName' => 'string|max:255',
            'lastName' => 'string|max:255',
            'email' => 'string|email|max:255',
            'phoneNumber' => 'string|max:11',
            'dateOfBirth' => 'date|before_or_equal:today',
            'address' => 'string|max:255',
            'typeOfDisability' => 'string|max:255',
            'pwdId' => 'string|max:19|regex:/^\d{2}-\d{4}-\d{3}-\d{7}$/',
            'upload_pwd_card' => 'file|mimes:jpg,jpeg,png,pdf|max:4096',
            'profilePicture' => 'file|mimes:jpg,jpeg,png|max:4096',
        ]);
        if ($request->hasFile('upload_pwd_card')) {
            $url = $supabase->upload($request->file('upload_pwd_card'), 'upload_pwd_card');
            $validateInformation['upload_pwd_card'] = $url;
        }
        if ($request->hasFile('profilePicture')) {
            $url = $supabase->upload($request->file('profilePicture'), 'profilePicture');
            $validateInformation['profilePicture'] = $url;
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
