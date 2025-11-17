<?php

namespace App\Http\Controllers;

use App\Exports\JobProviderUsersExport;
use App\Mail\EmailConfirmation;
use App\Mail\SendJobProviderAccountDeleteDetails;
use App\Mail\SentAccountActivationDetailsJobProvider;
use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AdminManageUserJobProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->notifications ?? collect();
        $unreadNotifications = $admin->unreadNotifications ?? collect();
        $users = users::all();
        $search = $request->input('search');
        $query = \App\Models\users::with(['province', 'city'])
            ->where('role', 'Job Provider')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(CONCAT("firstName",\' \' , "lastName")) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhere('userID', 'like', "%{$search}%")
                        ->orwhere('firstName', 'like', "%{$search}%")
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
        $sortable = ['userID', 'firstName', 'lastName', 'email', 'phoneNumber', 'companyName', 'role',];
        $sort = in_array($request->sort, $sortable) ? $request->sort : 'userID';
        $direction = $request->direction === 'desc' ? 'desc' : 'desc';

        $query->orderBy($sort, $direction);
        $users = $query->latest()->paginate(10);
        $response = response()->view('users.admin.manage_user_jobprovider', compact('admin', 'users', 'notifications', 'unreadNotifications', 'search'));
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
    public function update(string $id)
    {
        $user = users::findOrFail($id);
        $user->status = 'For Activation';
        $user->save();
        $maildata = [
            'userID' => $user->userID,
            'id' => $user->id,
            'email' => $user->email
        ];

        Mail::to($user)->send(new SentAccountActivationDetailsJobProvider($maildata));

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
    public function destroy(Request $request, string $id)
    {
        $validateInformation = $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);
        $user = users::findOrFail($id);
        Mail::to($user)->send(new SendJobProviderAccountDeleteDetails([
            'userID' => $user->userID,
            'remarks' => $validateInformation['remarks'],
        ]));
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new JobProviderUsersExport(
                $request->search,
                $request->sort,
                $request->direction
            ),
            'Job Providers Users.xlsx'
        );
    }
}
