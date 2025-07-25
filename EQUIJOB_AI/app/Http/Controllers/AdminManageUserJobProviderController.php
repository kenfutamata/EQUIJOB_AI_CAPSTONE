<?php

namespace App\Http\Controllers;

use App\Mail\EmailConfirmation;
use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $query = \App\Models\users::query()
            ->where('role', 'Job Provider')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%'])
                        ->orwhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('type_of_disability', 'like', "%{$search}%")
                        ->orWhere('pwd_id', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            });
        $sortable = ['userID', 'first_name', 'last_name', 'email', 'phone_number', 'company_name', 'role',];
        $sort = in_array($request->sort, $sortable) ? $request->sort : 'userID';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

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
        $user->status = 'Active';
        $user->save();
        Mail::to($user->email)->send(new EmailConfirmation());

        return redirect()->back()->with('Success', 'Email Successfully sent to user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = users::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('Delete_Success', 'User deleted successfully');
    }
}
