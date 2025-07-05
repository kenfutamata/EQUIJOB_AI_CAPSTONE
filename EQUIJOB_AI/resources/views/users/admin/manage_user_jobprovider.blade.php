<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Admin Manage Job Providers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <style>
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 234px;
            height: 100vh;
            z-index: 40;
            background-color: #c3d2f7;
        }

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

        .main-content-scroll {
            margin-left: 234px;
            margin-top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* Fade-out transition for notification bar */
        #notification-bar {
            transition: opacity 0.5s;
        }
    </style>
</head>

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
                </div>
                <form method="GET" action="{{ route('admin-manage-user-job-providers') }}" class="flex items-center gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Providers"
                        class="border rounded-l px-2 py-1 w-32 text-sm" />
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                </form>
            </div>

            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-4 py-3">User Id</th>
                            <th class="px-4 py-3">First Name</th>
                            <th class="px-4 py-3">Last Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Phone Number</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3">Company Logo</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Profile Picture</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                        @if($user->role == 'Job Provider')
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $user->userID }}</td>
                            <td class="px-4 py-3">{{ $user->first_name }}</td>
                            <td class="px-4 py-3">{{ $user->last_name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->phone_number }}</td>
                            <td class="px-4 py-3">{{ $user->company_name }}</td>
                            <td class="px-4 py-3">
                                @if ($user->company_logo)
                                <img src="{{ asset('storage/' . $user->company_logo) }}" alt="Company Logo" class="w-[60px] h-[60px] object-cover">
                                @else
                                No Logo
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $user->role }}</td>
                            <td class="px-2 py-2">
                                @if ($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-8 h-8 object-cover mx-auto">
                                @else No Picture @endif
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
                                <button onclick="openDeleteModal({{ $user->id }})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
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
                <input id="modal_first_name" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_last_name" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phone_number" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Company Name:</label>
                <input id="modal_company_name" class="w-full border rounded px-4 py-2 text-sm" disabled />
                <label class="block text-xs text-gray-500">Company Logo:</label>
                <img id="modal_company_logo" class="w-[100px] h-[100px] object-cover" />
                <label class="block text-xs text-gray-500">Business Permit:</label>
                <div id="modal_business_permit_container" />
            </div>
            <label class="block text-xs text-gray-500">Profile Picture:</label>
            <img id="modal_profile_picture" class="w-[100px] h-[100px] object-cover" />
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
                <h3 class="text-xl font-semibold">Delete User?</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deleteuser" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-gray-50">Yes</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>

    <script>
        function openProfileModal(button) {
            const user = JSON.parse(button.getAttribute('data-user'));
            document.getElementById('modal_first_name').value = user.first_name;
            document.getElementById('modal_last_name').value = user.last_name;
            document.getElementById('modal_email').value = user.email;
            document.getElementById('modal_phone_number').value = user.phone_number;
            document.getElementById('modal_company_name').value = user.company_name;
            const permitContainer = document.getElementById('modal_business_permit_container');
            permitContainer.innerHTML = '';

            if (user.business_permit) {
                const fileExtension = user.business_permit.split('.').pop().toLowerCase();
                const filePath = `/storage/${user.business_permit}`;

                if (['jpg', 'jpeg', 'png', 'webp'].includes(fileExtension)) {
                    permitContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
                } else if (fileExtension === 'pdf') {
                    permitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Business Permit (PDF)</a>`;
                } else {
                    permitContainer.innerText = 'Unsupported file format';
                }
            }
            document.getElementById('modal_profile_picture').value = user.profile_picture;
            document.getElementById('modal_role').value = user.role;
            document.getElementById('modal_status').value = user.status;

            const logo = document.getElementById('modal_company_logo');
            if (user.company_logo) {
                logo.src = `/storage/${user.company_logo}`;
                logo.style.display = 'block';
            } else {
                logo.style.display = 'none';
            }

            document.getElementById('viewProfileModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('viewProfileModal').classList.add('hidden');
        }

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteuser');
            form.action = `/EQUIJOB/Admin/Manage-User-JobProviders/Delete/${userId}`;
            document.getElementById('DeleteRoleModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteRoleModal').classList.add('hidden');
        }

        // Fade out notification bar after 2.5s, then hide after 3s
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