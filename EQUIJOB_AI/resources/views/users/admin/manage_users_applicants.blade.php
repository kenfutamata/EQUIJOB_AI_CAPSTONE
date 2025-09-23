<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Admin Manage User Applicants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <style>
        /* Ensure sidebar is always fixed */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 234px;
            height: 100vh;
            z-index: 40;
            background-color: #c3d2f7;
        }

        /* Ensure topbar is always fixed */
        .topbar-fixed {
            position: fixed;
            top: 0;
            left: 234px;
            right: 0;
            height: 64px;
            z-index: 30;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Main content below topbar */
        .main-content-scroll {
            margin-left: 234px;
            margin-top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            padding: 1rem;
        }

        #notification-bar {
            transition: opacity 0.5s;
        }
    </style>
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
                    <span class="text-blue-500">User Applicants</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin-manage-user-applicants') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Applicants</a>
                    <a href="{{ route('admin-manage-user-job-providers') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Job Providers</a>
                </div>
                <form method="GET" action="{{ route('admin-manage-user-applicants') }}" class="flex items-center gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Applicants"
                        class="border rounded-l px-2 py-1 w-32 text-sm" />
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                </form>
            </div>
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-4 py-3">User Id {!! sortArrow('userID')!!}</th>
                            <th class="px-2 py-2">First Name {!! sortArrow('firstName')!!}</th>
                            <th class="px-2 py-2">Last Name {!! sortArrow('lastName')!!}</th>
                            <th class="px-2 py-2 max-w-[150px] break-words">Email {!! sortArrow('email')!!}</th>
                            <th class="px-2 py-2">Phone {!! sortArrow('phoneNumber')!!}</th>
                            <th class="px-2 py-2">DOB {!! sortArrow('dateOfBirth')!!}</th>
                            <th class="px-2 py-2">Disability {!! sortArrow('typeOfDisability')!!}</th>
                            <th class="px-2 py-2">Profile Picture</th>
                            <th class="px-2 py-2">Role {!! sortArrow('role')!!}</th>
                            <th class="px-2 py-2">Status {!! sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $user->userID}}</td>
                            <td class="px-2 py-2">{{ $user->firstName }}</td>
                            <td class="px-2 py-2">{{ $user->lastName }}</td>
                            <td class="px-2 py-2 break-all max-w-[150px]">{{ $user->email }}</td>
                            <td class="px-2 py-2">{{ $user->phoneNumber }}</td>
                            <td class="px-2 py-2">{{ $user->dateOfBirth }}</td>
                            <td class="px-2 py-2">{{ $user->typeOfDisability }}</td>
                            <td class="px-2 py-2">
                                @if ($user->profilePicture)
                                <img src="{{ asset('storage/' . $user->profilePicture) }}" class="w-8 h-8 object-cover mx-auto">
                                @else No Picture @endif
                            </td>
                            <td class="px-2 py-2">{{ $user->role }}</td>
                            <td class="px-2 py-2">{{ $user->status }}</td>
                            <td class="px-2 py-2 space-y-1">
                                <button onclick="openProfileModal(this)" data-user='@json($user)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @if($user->status == 'Inactive')
                                <form action="{{ route('admin-manage-user-applicants-accept', $user->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Accept</button>
                                </form>
                                @endif
                                <button onclick="openDeleteModal('{{route('admin-manage-user-applicants-delete', $user->id)}}')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
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
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h2 class="text-xl font-bold mb-4">Applicant Profile</h2>
            <div class="space-y-2">
                <label class="block text-xs text-gray-500">First Name:</label>
                <input id="modal_firstName" class="w-full border rounded px-2 py-1" readonly placeholder="First Name">
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_lastName" class="w-full border rounded px-2 py-1" readonly placeholder="Last Name">
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-2 py-1" readonly placeholder="Email">
                <label class="block text-xs text-gray-500">Address:</label>
                <input id="modal_address" class="w-full border rounded px-2 py-1" readonly placeholder="Address">
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phoneNumber" class="w-full border rounded px-2 py-1" readonly placeholder="Phone Number">
                <label class="block text-xs text-gray-500">Date of Birth:</label>
                <input id="modal_dateOfBirth" class="w-full border rounded px-2 py-1" readonly placeholder="Date of Birth">
                <label class="block text-xs text-gray-500">PWD ID:</label>
                <input id="modal_pwdId" class="w-full border rounded px-2 py-1" readonly placeholder="PWD ID">
                <label class="block text-xs text-gray-500">Disability:</label>
                <input id="typeOfDisability" class="w-full border rounded px-2 py-1" readonly placeholder="Type of Disability">
                <div>
                    <label class="block text-xs text-gray-500">PWD Card:</label>
                    <img id="modal_pwd_card" class="w-16 h-16 object-cover border rounded" style="display:none;">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Profile Picture:</label>
                    <img id="modal_profilePicture" class="w-16 h-16 object-cover border rounded" style="display:none;">
                </div>
                <label class="block text-xs text-gray-500">Role:</label>
                <input id="modal_role" class="w-full border rounded px-2 py-1" readonly placeholder="Role">
                <label class="block text-xs text-gray-500">Status:</label>
                <input id="modal_status" class="w-full border rounded px-2 py-1" readonly placeholder="Status">
            </div>
        </div>
    </div>

    <div id="DeleteRoleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Delete User?</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deleteuser" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-200">Yes</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">No</button>
        </div>
    </div>


    <script>
        function openProfileModal(button) {
            const user = JSON.parse(button.getAttribute('data-user'));
            document.getElementById('modal_firstName').value = user.firstName;
            document.getElementById('modal_lastName').value = user.lastName;
            document.getElementById('modal_email').value = user.email;
            document.getElementById('modal_address').value = user.address;
            document.getElementById('modal_phoneNumber').value = user.phoneNumber;
            document.getElementById('modal_dateOfBirth').value = user.dateOfBirth;
            document.getElementById('modal_pwdId').value = user.pwdId;
            document.getElementById('typeOfDisability').value = user.typeOfDisability;
            const pwdCardImg = document.getElementById('modal_pwd_card');
            pwdCardImg.src = user.upload_pwd_card ? `/storage/${user.upload_pwd_card}` : '';
            pwdCardImg.style.display = user.upload_pwd_card ? 'block' : 'none';
            const profilePictureImg = document.getElementById('modal_profilePicture');
            profilePictureImg.src = user.profilePicture ? `/storage/${user.profilePicture}` : '';
            profilePictureImg.style.display = user.profilePicture ? 'block' : 'none';
            document.getElementById('modal_role').value = user.role;
            document.getElementById('modal_status').value = user.status;
            document.getElementById('viewProfileModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('viewProfileModal').classList.add('hidden');
        }

        function openDeleteModal(url) {
            const form = document.getElementById('deleteuser');
            form.action = url;
            document.getElementById('DeleteRoleModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteRoleModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('viewProfileModal');
            if (e.target === modal) closeModal();
        });

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 2500);
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);
    </script>
</body>

</html>