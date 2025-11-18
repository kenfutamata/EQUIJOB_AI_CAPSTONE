<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\User; // <-- CHANGE THIS: Use the conventional User model
use App\Models\users;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicantProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('applicant')->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $provinces = Province::all(); 

        $response = response()->view('users.applicant.applicant_profile', compact('user', 'notifications', 'unreadNotifications', 'provinces'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupabaseStorageService $supabase)
    {

        $validatedData = $request->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phoneNumber' => 'nullable|string|max:11',
            'dateOfBirth' => 'nullable|date|before_or_equal:today',
            'address' => 'nullable|string|max:255',
            'provinceId'=> 'nullable|exists:provinces,id',
            'cityId'=> 'nullable|exists:cities,id',
            'typeOfDisability' => 'nullable|string|max:255',
            'pwdId' => 'nullable|string|max:19|regex:/^\d{2}-\d{4}-\d{3}-\d{7}$/',
            'upload_pwd_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'profilePicture' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('upload_pwd_card')) {
            $url = $supabase->upload($request->file('upload_pwd_card'), 'upload_pwd_card');
            $validatedData['upload_pwd_card'] = $url;
        }

        if ($request->hasFile('profilePicture')) {
            $url = $supabase->upload($request->file('profilePicture'), 'profilePicture');
            $validatedData['profilePicture'] = $url;
        }

        try {
            $user = Auth::guard('applicant')->user();
            $user->update($validatedData);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the profile.');
        }
    }

}