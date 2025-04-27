<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
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
            'type_of_disability' => 'required|string|max:255',
            'pwd_card' => 'nullable|string|max:255',
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'role' => 'required|in:job_provider',
        ]);

        return redirect()->route('email-confirmation')->with('success', 'Request Successful! Please wait for admins to approve your account.');    }

    public function ViewEmailConfirmationPage(){
        return view ('sign-in-page.sign_up.sign_up_confirmation.registrationMessage'); 
    }
}
