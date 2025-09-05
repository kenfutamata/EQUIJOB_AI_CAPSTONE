<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordDetails;
use App\Mail\ForgotPasswordDetailsMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordPage()
    {
        return view('sign-in-page.forgot-password.forgot_password');
    }

    public function validateEmail(Request $request)
    {
        $ValidateInformation = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $ValidateInformation['email'])->first();
            if ($user) {
                $maildata = [
                    'email' => $request->email,
                ];
                Mail::to($request->email)->send(new ForgotPasswordDetailsMail($maildata));
                return view('sign-in-page.forgot-password.password_request_sent', compact('user'))->with('Success', 'Password reset details have been sent to your email.');
            } else {
                return back()->withErrors(['error' => 'No user found with this email address.']);
            }
        } catch (\Exception $e) {
            Log::error('Error Occured. Please try again later. ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error Occured. Please try again later.']);
        }
    }

    public function showUpdatePasswordPage(Request $request)
    {
        $email = $request->input('email');
        return view('sign-in-page.forgot-password.password_update', compact('email'));
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = bcrypt($request->password);
            $user->save();
            return view('sign-in-page.forgot-password.password_updated')->with('Success', 'Your password has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error Occured. Please try again later. ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error Occured. Please try again later.']);
        }
    }
}
