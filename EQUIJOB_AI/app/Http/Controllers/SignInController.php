<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Province;
use App\Models\users;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SignInController extends Controller
{
    public function ViewSignUpApplicantPage()
    {
        $provinces = Province::all();
        $cities = collect(); 
        return view('sign-in-page.sign_up.sign_up_applicant', compact('provinces'));
    }

    public function ViewSignUpJobProviderPage()
    {
        $provinces = Province::all();

        return view('sign-in-page.sign_up.sign_up_job_provider', compact('provinces'));
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
    public function generateAlphaNumericId(string $role): string
    {
        $prefix = match ($role) {
            'Applicant' => 'JA25',
            'Job Provider' => 'JP25',
            default => 'XX25',
        };

        $last = users::whereNotNull('userID')
            ->where('userID', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $lastID = $last?->userID ?? $prefix . '0000';
        $number = (int) substr($lastID, strlen($prefix));
        $next = $number + 1;
    return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
    public function SignUpJobApplicant(Request $request, SupabaseStorageService $supabase)
    {
        $validateInformation =  $request->validate([
            'firstName' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'lastName' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phoneNumber' => 'required|string|max:11',
            'dateOfBirth' => 'required|date|before_or_equal:today',
            'address' => 'required|string|max:255',
            'provinceId' => 'required|exists:provinces,id',
            'cityId' => 'required|exists:cities,id',
            'gender' => 'required|string|max:255|',
            'typeOfDisability' => 'required|string|max:255',
            'pwdId' => 'nullable|string|max:19|regex:/^\d{2}-\d{4}-\d{3}-\d{7}$/|unique:users',
            'upload_pwd_card' => 'required|file|mimes:jpg,jpeg,png|max:4096',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        if ($request->hasFile('upload_pwd_card')) {
            $url = $supabase->upload($request->file('upload_pwd_card'), 'upload_pwd_card');
            $validateInformation['upload_pwd_card'] = $url;
        }
        $validateInformation['role'] = $validateInformation['role'] ?? 'Applicant';
        $validateInformation['status'] = $validateInformation['status'] ?? 'Inactive';
        $validateInformation['password'] = Hash::make($request->password);
        $validateInformation['userID'] = $this->generateAlphaNumericId('Applicant');
        try {
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }
    public function SignUpJobProvider(Request $request, SupabaseStorageService $supabase)
    {
        $validateInformation = $request->validate([
            'firstName' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'lastName' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phoneNumber' => 'required|string|max:15',
            'companyName' => 'required|string|max:255|',
            'companyAddress' => 'required|string|max:100',
            'provinceId' => 'required|exists:provinces,id',
            'cityId' => 'required|exists:cities,id',
            'companyLogo' => 'required|file|mimes:jpg,jpeg,png|max:4096',
            'businessPermit' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        try {
            if ($request->hasFile('companyLogo')) {
                $url = $supabase->upload($request->file('companyLogo'), 'companyLogo');
                $validateInformation['companyLogo'] = $url;
            }
            if ($request->hasFile('businessPermit')) {
                $url = $supabase->upload($request->file('businessPermit'), 'businessPermit');
                $validateInformation['businessPermit'] = $url;
            }
            $validateInformation['role'] = $validateInformation['role'] ?? 'Job Provider';
            $validateInformation['status'] = $validateInformation['status'] ?? 'Inactive';
            $validateInformation['password'] = Hash::make($request->password);
            $validateInformation['userID'] = $this->generateAlphaNumericId('Job Provider');
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
        return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
    }

    public function LoginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        $adminCredentials = array_merge($credentials, ['role' => 'Admin', 'status' => 'Active']);
        if (Auth::guard('admin')->attempt($adminCredentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin-dashboard')->with('success', 'Login Successful!');
        }

        $applicantCredentials = array_merge($credentials, ['role' => 'Applicant', 'status' => 'Active']);
        if (Auth::guard('applicant')->attempt($applicantCredentials)) {
            $request->session()->regenerate();
            return redirect()->route('applicant-dashboard')->with('success', 'Login Successful!');
        }

        $jobProviderCredentials = array_merge($credentials, ['role' => 'Job Provider', 'status' => 'Active']);
        if (Auth::guard('job_provider')->attempt($jobProviderCredentials)) {
            $request->session()->regenerate();
            return redirect()->route('job-provider-dashboard')->with('success', 'Login Successful!');
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid credentials or your account is not yet active.');
    }
    public function ViewEmailConfirmationPage()
    {
        return view('sign-in-page.sign_up.sign_up_confirmation.registrationMessage');
    }
}
