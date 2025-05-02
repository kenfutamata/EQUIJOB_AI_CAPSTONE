<?php

namespace App\Http\Controllers;
use App\Models\users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignInController extends Controller
{
    public function ViewSignUpApplicantPage(){
        return view ('sign-in-page.sign_up.sign_up_applicant'); 
    }

    public function ViewSignUpJobProviderPage(){
        return view ('sign-in-page.sign_up.sign_up_job_provider'); 
    }

    public function SignUpJobApplicant(Request $request){
       $validateInformation =  $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:11',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'type_of_disability' => 'required|string|max:255',
            'pwd_card' => 'nullable|string|max:12',
            'upload_pwd_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        if($request->hasFile('upload_pwd_card')){
            $file = $request->file('upload_pwd_card'); 
            $filepath = $file->store('upload_pwd_card', 'public'); 
            $validateInformation['upload_pwd_card'] = $filepath;
        }
        $validateInformation['role']=$validateInformation['role']??'applicant';
        $validateInformation['status']=$validateInformation['status']??'inactive'; 
        $validateInformation['password'] = Hash::make($request->password); 
        try{
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }
    public function SignUpJobProvider(Request $request){
        $validateInformation = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        try{
            if($request->hasFile('company_logo')){
                $file = $request->file('company_logo'); 
                $filepath = $file->store('company_logo', 'public'); 
                $validateInformation['company_logo'] = $filepath;
            }
            
            $validateInformation['role']=$validateInformation['role']??'job_provider';
            $validateInformation['status']=$validateInformation['status']??'inactive'; 
            $validateInformation['password'] = Hash::make($request->password); 
            users::create($validateInformation);
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
            return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');    
        }    
    
        public function LoginUser(Request $request){
            $validateInformation = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);
            try{
                $credentiasls = $request->only('email', 'password');
                if (Auth::guard('admin')->attempt($credentiasls)) {   
                    $user = Auth::guard('admin')->user();
                    if($user->role===('admin')&& $user->status===('active')){
                        dd('Admin'); 
                    return redirect()->intended('dashboard')->with('success', 'Login Successful!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
                }

                if (Auth::guard('applicant')->attempt($credentiasls)) {   
                    $user = Auth::guard('applicant')->user();
                    if($user->role===('applicant')&& $user->status===('active')){
                        dd('applicant'); 
                        // return redirect()->intended('dashboard')->with('success', 'Login Successful!');
                    // return redirect()->intended('dashboard')->with('success', 'Login Successful!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
                }

                if (Auth::guard('job_provider')->attempt($credentiasls)) {   
                    $user = Auth::guard('job_provider')->user();
                    if($user->role===('job_provider')&& $user->status===('active')){ 
                        return redirect()->route('job-provider-dashboard');                    }
                } else {
                    return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
                }
            }catch(\Exception $e){
                return redirect()->back()->with('catch_error', 'An error occurred while logging in: ' . $e->getMessage());
            }
        }
        public function ViewEmailConfirmationPage(){
        return view ('sign-in-page.sign_up.sign_up_confirmation.registrationMessage'); 
    }
    
}
