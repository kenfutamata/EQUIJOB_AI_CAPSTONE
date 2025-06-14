<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SignInController extends Controller
{
    public function ViewSignUpApplicantPage()
    {
        return view('sign-in-page.sign_up.sign_up_applicant');
    }

    public function ViewSignUpJobProviderPage()
    {
        return view('sign-in-page.sign_up.sign_up_job_provider');
    }

    public function SignUpJobApplicant(Request $request)
    {
        $validateInformation =  $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:11',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'address' => 'required|string|max:255',
            'gender'=> 'required|string|max:255|', 
            'type_of_disability' => 'required|string|max:255',
            'pwd_id' => 'nullable|string|max:12|regex:/^\d{3}-\d{3}-\d{3}$/',
            'upload_pwd_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        if ($request->hasFile('upload_pwd_card')) {
            $file = $request->file('upload_pwd_card');
            $filepath = $file->store('upload_pwd_card', 'public');
            $validateInformation['upload_pwd_card'] = $filepath;
        }
        $validateInformation['role'] = $validateInformation['role'] ?? 'Applicant';
        $validateInformation['status'] = $validateInformation['status'] ?? 'Inactive';
        $validateInformation['password'] = Hash::make($request->password);
        try {
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }
    public function SignUpJobProvider(Request $request)
    {
        $validateInformation = $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'company_name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'company_logo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        try {
            if ($request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $filepath = $file->store('company_logo', 'public');
                $validateInformation['company_logo'] = $filepath;
            }

            $validateInformation['role'] = $validateInformation['role'] ?? 'Job Provider';
            $validateInformation['status'] = $validateInformation['status'] ?? 'Inactive';
            $validateInformation['password'] = Hash::make($request->password);
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
        return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
    }

    public function LoginUser(Request $request)
    {
        $validateInformation = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        try {
            // Admin
            $adminCredentials = $request->only('email', 'password');
            $adminCredentials['role'] = 'Admin';
            $adminCredentials['status'] = 'Active';
            if (Auth::guard('admin')->attempt($adminCredentials)) {
                $user = Auth::guard('admin')->user();
                if ($user->role === 'Admin' && $user->status === 'Active') {
                    $request->session()->regenerate();
                    return redirect()->route('admin-dashboard')->with('success', 'Login Successful!');
                } else {
                    dd('Admin found, but role/status mismatch', $user);
                }
            } else {
                // Debug failed attempt
                Log::info('Admin guard failed', $adminCredentials);
            }

            // Applicant
            $applicantCredentials = $request->only('email', 'password');
            $applicantCredentials['role'] = 'Applicant';
            $applicantCredentials['status'] = 'Active'; 
            if (Auth::guard('applicant')->attempt($applicantCredentials)) {
                $user = Auth::guard('applicant')->user();
                if ($user->role === 'Applicant' && $user->status === 'Active') {
                    $request->session()->regenerate();
                    return redirect()->route('applicant-dashboard');
                } else {
                    dd('Applicant found, but role/status mismatch', $user);
                }
            } else {
                Log::info('Applicant guard failed', $applicantCredentials);
            }

            // Job Provider
            $jobProviderCredentials = $request->only('email', 'password');
            $jobProviderCredentials['role'] = 'Job Provider';
            $jobProviderCredentials['status'] = 'Active';
            if (Auth::guard('job_provider')->attempt($jobProviderCredentials)) {
                $user = Auth::guard('job_provider')->user();
                if ($user->role === 'Job Provider' && $user->status === 'Active') {
                    $request->session()->regenerate();
                    return redirect()->route('job-provider-dashboard');
                } else {
                    dd('Job Provider found, but role/status mismatch', $user);
                }
            } else {
                Log::info('Job Provider guard failed', $jobProviderCredentials);
            }

            return redirect()->back()->with('error', 'Invalid credentials or inactive account. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
        }
    }
    public function ViewEmailConfirmationPage()
    {
        return view('sign-in-page.sign_up.sign_up_confirmation.registrationMessage');
    }
}
