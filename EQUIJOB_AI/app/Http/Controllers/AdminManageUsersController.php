<?php

namespace App\Http\Controllers;

use App\Exports\JobApplicantUsersExport;
use App\Mail\EmailConfirmation;
use App\Mail\SendAccountActivationDetailsJobApplicant;
use App\Mail\SendAccountDeleteDetails;
use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AdminManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->notifications ?? collect();
        $unreadNotifications = $admin->unreadNotifications ?? collect();
        $search = $request->input('search');

        $query = \App\Models\users::with(['province', 'city'])->where('role', 'Applicant')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(CONCAT("firstName", \' \', "lastName")) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhere('userID', 'like', "%{$search}%")
                        ->orWhere('firstName', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phoneNumber', 'like', "%{$search}%")
                        ->orWhere('typeOfDisability', 'like', "%{$search}%")
                        ->orWhere('pwdId', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('province', function ($q) use ($search) {
                            $q->where('provinceName', 'like', "%{$search}%");
                        })
                        ->orWhereHas('city', function ($q) use ($search) {
                            $q->where('cityName', 'like', "%{$search}%");
                });
            });
        });
        $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'dateOfBirth', 'typeOfDisability', 'role', 'status'];
        $sort = in_array($request->sort, $sortable) ? $request->sort : 'userID';
        $direction = $request->direction === 'desc' ? 'desc' : 'desc';

        $query->orderBy($sort, $direction);
        $users = $query->orderBy($sort, $direction)->paginate(10);
        $response = response()->view('users.admin.manage_users_applicants', compact('admin', 'users', 'notifications', 'unreadNotifications', 'search'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    public function export(Request $request)
    {
        return Excel::download(
            new JobApplicantUsersExport(
                $request->search,
                $request->sort,
                $request->direction
            ),
            'job_applicants.xlsx'
        );
    }

    public function viewActivatedAccountPage()
    {
        return view('sign-in-page.sign_up.sign_up_confirmation.account_activated');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

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
    public function update(string $id)
    {
        $user = users::findOrFail($id);
        $maildata = [
            'id' => $user->id,
            'userID' => $user->userID,
            'email' => $user->email,
        ];
        $user->status = 'For Activation';
        $user->save();
        Mail::to($user)->send(new SendAccountActivationDetailsJobApplicant($maildata));
        return redirect()->back()->with('Success', 'Email Successfully sent to user');
    }

    public function ActivateAccount(string $id)
    {
        $user = users::findOrFail($id);
        $user->status = 'Active';
        $user->save();

        return redirect()->route('account-activated')->with('success', 'Account Activated Successfully! You may now login.');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = users::findOrFail($id);

        Mail::to($user)->send(new SendAccountDeleteDetails([
            'userID' => $user->userID,
         ]));
        $user->delete();
        return redirect()->back()->with('Success', 'User deleted successfully');
    }
}
