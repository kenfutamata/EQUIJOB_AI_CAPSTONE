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
            'gender' => 'required|string|max:255|',
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
        $validateInformation['userID'] = $this->generateAlphaNumericId('Applicant');
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
            'company_name' => 'required|string|max:255|',
            'company_logo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'business_permit' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        try {
            if ($request->hasFile('company_logo')) {
                $file = $request->file('company_logo');
                $filepath = $file->store('company_logo', 'public');
                $validateInformation['company_logo'] = $filepath;
            }
            if ($request->hasFile('business_permit')) {
                $file = $request->file('business_permit');
                $filepath = $file->store('business_permit', 'public');
                $validateInformation['business_permit'] = $filepath;
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
