<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Province;
use App\Models\users;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobProviderProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authenticatedUser = Auth::guard('job_provider')->user();
        if (!$authenticatedUser) {
            return redirect()->route('login');
        }
        $user = users::with(['province', 'city'])->findOrFail($authenticatedUser->id);
        $provinces = Province::all();
        $notification = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        $cities = [];
        $selectedProvinceId = old('provinceId', $user->provinceId);
        if ($selectedProvinceId) {
            $cities = Cities::where('provinceId', $selectedProvinceId)->get();
        }

        $response = response()->view('users.job-provider.job_provider_profile', compact('user', 'notification', 'unreadNotifications', 'provinces', 'cities'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }


    public function getCities(Province $province)
    {
        $citiesCollection = $province->cities;
        $citiesArray = $citiesCollection->map(function ($city) {
            return [
                'id' => $city->id,
                'cityName' => $city->cityName,
            ];
        });

        return response()->json($citiesArray);
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
            'firstName' => 'sometimes|string|max:100',
            'lastName' => 'sometimes|string|max:100',
            'email' => 'sometimes|string|email|max:255',
            'businessPermit' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'companyName' => 'sometimes|string|max:100',
            'companyLogo' => 'sometimes|file|mimes:jpg,jpeg,png|max:4096',
            'profilePicture' => 'sometimes|file|mimes:jpg,jpeg,png|max:4096',
            'phoneNumber' => 'sometimes|string|max:11',
            'companyAddress' => 'sometimes|string|max:100',
            'provinceId'=> 'exists:provinces,id', 
            'cityId'=> 'exists:cities,id',
        ]);
        if ($request->hasFile('companyLogo')) {
            $url = $supabase->upload($request->file('companyLogo'), 'companyLogo');
            $validateInformation['companyLogo'] = $url;
        }
        if ($request->hasFile('businessPermit')) {
            $url = $supabase->upload($request->file('businessPermit'), 'businessPermit');
            $validateInformation['businessPermit'] = $url;
        }
        if ($request->hasFile('profilePicture')) {
            $url = $supabase->upload($request->file('profilePicture'), 'profilePicture');
            $validateInformation['profilePicture'] = $url;
        }

        try {
            $userId = Auth::guard('job_provider')->id();

            $user = \App\Models\users::findOrFail($userId);
            $user->update($validateInformation);
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('error', "Error Occured" . $e->getMessage());

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
