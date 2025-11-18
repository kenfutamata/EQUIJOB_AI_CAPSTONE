<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Admin Manage Job Providers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/manage_users/css/manage_user_jobprovider.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">

</head>

@php
function sortArrow($column) {
$currentSort = request('sort');
$direction = request('direction') === 'asc' ? 'desc' : 'asc';
$arrow = request('sort') === $column
? (request('direction') === 'asc' ? '↑' : '↓')
: '↕';
$params = array_merge(request()->all(), ['sort' => $column, 'direction' => $direction]);
$url = request()->url() . '?' . http_build_query($params);
return "<a href=\"$url\" class=\"text-xs\">$arrow</a>";
}
@endphp

<body class="bg-white text-black">
    <div>
        <div class="sidebar-fixed">
            <x-admin-sidebar />
        </div>
        <div class="topbar-fixed">
            <x-topbar :user="$admin" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
        </div>
        <main class="main-content-scroll">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Success') }}
            </div>
            @elseif(session('Delete_Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Delete_Success') }}
            </div>
            @endif

            <div class="text-4xl font-audiowide mb-6 flex items-center justify-between">
                <div>
                    <span class="text-gray-800">Manage </span>
                    <span class="text-blue-500">Job Providers</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin-manage-user-applicants') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Applicants</a>
                    <a href="{{ route('admin-manage-user-job-providers') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Job Providers</a>
                    <a href="{{ route('admin-manage-user-jobproviders-export') }}" class="bg-green-500 text-white px-2 py-1 rounded text-base">Export to Excel</a>

                </div>
                <form method="GET" action="{{ route('admin-manage-user-job-providers') }}" class="flex items-center gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Providers"
                        class="border rounded-l px-2 py-1 w-32 text-sm" />
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                </form>
            </div>

            <table class="min-w-full text-sm text-center">
                <thead class="bg-gray-100 font-semibold">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">User Id {!! sortArrow('userID')!!}</th>
                        <th class="px-4 py-3">First Name {!! sortArrow('firstName')!!}</th>
                        <th class="px-4 py-3">Last Name {!! sortArrow('lastName')!!}</th>
                        <th class="px-4 py-3">Email {!! sortArrow('email')!!}</th>
                        <th class="px-4 py-3">Company {!! sortArrow('companyName')!!}</th>
                        <th class="px-4 py-3">Company Logo</th>
                        <th class="px-4 py-3">Profile Picture</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">{{ $user->userID }}</td>
                        <td class="px-4 py-3">{{ $user->firstName }}</td>
                        <td class="px-4 py-3">{{ $user->lastName }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->companyName }}</td>
                        <td class="px-4 py-3">
                            @if ($user->companyLogo)
                            @php
                            $logoUrl = Str::startsWith($user->companyLogo, 'http')
                            ? $user->companyLogo
                            : "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage/companyLogo/{$user->companyLogo}";
                            @endphp
                            <img src="{{ $logoUrl }}" alt="Company Logo" class="w-[30px] h-[30px] object-cover rounded">
                            @else
                            No Logo
                            @endif
                        </td>
                        <td class="px-2 py-2">
                            @if ($user->profilePicture)
                            @php
                            $profileUrl = Str::startsWith($user->profilePicture, 'http')
                            ? $user->profilePicture
                            : "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage/profilePicture/{$user->profilePicture}";
                            @endphp
                            <img src="{{$profileUrl}}" class="w-8 h-8 object-cover mx-auto">
                            @else
                            No Picture
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $user->status }}</td>
                        <td class="px-4 py-3 space-y-1">
                            <button type="button"
                                onclick="openProfileModal(this)"
                                data-user='@json($user)'
                                class="bg-blue-500 text-white px-2 py-1 rounded">View</button>

                            @if($user->status === 'Inactive')
                            <form action="{{ route('admin-manage-user-Job-Providers-accept', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Accept</button>
                            </form>
                            @endif
                            <button onclick="openDeleteModal('{{route('admin-manage-user-job-providers-delete', $user->id)}}')"
                                class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 flex justify-center">
                {!! $users->links('pagination::tailwind') !!}
            </div>
    </div>
    </main>
    </div>

    <div id="viewProfileModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Job Provider Profile</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="space-y-4">
                <label class="block text-xs text-gray-500">First Name:</label>
                <input id="modal_firstName" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_lastName" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phoneNumber" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Company Name:</label>
                <input id="modal_companyName" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Company Address:</label>
                <input id="modal_companyAddress" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Province:</label>
                <input id="modal_province" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">City:</label>
            <input id="modal_city" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Company Logo:</label>
                <img id="modal_companyLogo" class="w-[100px] h-[100px] object-cover" />
                <label class="block text-xs text-gray-500">Business Permit:</label>
                <div id="modal_businessPermit_container">
                </div>
                <label class="block text-xs text-gray-500">Profile Picture:</label>
                <img id="modal_profilePicture" class="w-[100px] h-[100px] object-cover" />
                <label class="block text-xs text-gray-500">Role:</label>
                <input id="modal_role" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Status:</label>
                <input id="modal_status" class="w-full border rounded px-4 py-2 text-sm" disabled />
            </div>
        </div>
    </div>

    <div id="DeleteRoleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Please Input your Remarks for Deleting Job Provider Account</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deleteJobProviderAccountForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="4" class="w-full border rounded px-2 py-1" required></textarea>
                    <button type="submit" class="w-full py-3 px-4 rounded-lg bg-red-500 text-white">Delete</button>
            </form>
        </div>
</body>
<script src="{{ asset('assets/admin/manage_users/js/manage_user_jobprovider.js') }}"></script>

</html>