<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Admin Manage User Applicants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>

<body class="bg-white text-black">
    <div class="flex min-h-screen overflow-hidden">
        <div class="fixed top-0 left-0 w-[234px] h-full z-40 bg-[#c3d2f7]">
            <x-admin-sidebar />
        </div>

        <div class="flex-1 flex flex-col min-h-screen pl-[234px]">
            <header class="w-full border-b border-gray-200">
                <x-topbar :user="$admin" />
            </header>

            <main class="p-4 overflow-x-auto">
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Providers"
                            class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-gray-100 font-semibold">
                            <tr>
                                <th class="px-2 py-2">Id</th>
                                <th class="px-2 py-2">First Name</th>
                                <th class="px-2 py-2">Last Name</th>
                                <th class="px-2 py-2 max-w-[150px] break-words">Email</th>
                                <th class="px-2 py-2 max-w-[150px] break-words">Address</th>
                                <th class="px-2 py-2">Phone</th>
                                <th class="px-2 py-2">DOB</th>
                                <th class="px-2 py-2">Disability</th>
                                <th class="px-2 py-2">PWD ID</th>
                                <th class="px-2 py-2">PWD Card</th>
                                <th class="px-2 py-2">Profile Pic</th>
                                <th class="px-2 py-2">Role</th>
                                <th class="px-2 py-2">Status</th>
                                <th class="px-2 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                            @if($user->role == 'applicant')
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2">{{ $user->id }}</td>
                                <td class="px-2 py-2">{{ $user->first_name }}</td>
                                <td class="px-2 py-2">{{ $user->last_name }}</td>
                                <td class="px-2 py-2 break-all max-w-[150px]">{{ $user->email }}</td>
                                <td class="px-2 py-2 break-words max-w-[150px]">{{ $user->address }}</td>
                                <td class="px-2 py-2">{{ $user->phone_number }}</td>
                                <td class="px-2 py-2">{{ $user->date_of_birth }}</td>
                                <td class="px-2 py-2">{{ $user->type_of_disability }}</td>
                                <td class="px-2 py-2">{{ $user->pwd_id }}</td>
                                <td class="px-2 py-2">
                                    @if ($user->upload_pwd_card)
                                    <img src="{{ asset('storage/' . $user->upload_pwd_card) }}" class="w-8 h-8 object-cover mx-auto">
                                    @else No Card @endif
                                </td>
                                <td class="px-2 py-2">
                                    @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-8 h-8 object-cover mx-auto">
                                    @else No Picture @endif
                                </td>
                                <td class="px-2 py-2">{{ $user->role }}</td>
                                <td class="px-2 py-2">{{ $user->status }}</td>
                                <td class="px-2 py-2 space-y-1">
                                    <button onclick="openProfileModal(this)" data-user='@json($user)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                    @if($user->status == 'inactive')
                                    <form action="{{ route('admin-manage-user-applicants-accept', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Accept</button>
                                    </form>
                                    @endif
                                    <button onclick="openDeleteModal({{ $user->id }})" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
        </main>
    </div>
    </div>
    <div id="viewProfileModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h2 class="text-xl font-bold mb-4">Applicant Profile</h2>
            <div class="space-y-2">
                <label class="block text-xs text-gray-500">First Name:</label>
                <input id="modal_first_name" class="w-full border rounded px-2 py-1" readonly placeholder="First Name">
                <label class="block text-xs text-gray-500">Last Name:</label>
                <input id="modal_last_name" class="w-full border rounded px-2 py-1" readonly placeholder="Last Name">
                <label class="block text-xs text-gray-500">Email Address:</label>
                <input id="modal_email" class="w-full border rounded px-2 py-1" readonly placeholder="Email">
                <label class="block text-xs text-gray-500">Address:</label>
                <input id="modal_address" class="w-full border rounded px-2 py-1" readonly placeholder="Address">
                <label class="block text-xs text-gray-500">Phone Number:</label>
                <input id="modal_phone_number" class="w-full border rounded px-2 py-1" readonly placeholder="Phone Number">
                <label class="block text-xs text-gray-500">Date of Birth:</label>
                <input id="modal_date_of_birth" class="w-full border rounded px-2 py-1" readonly placeholder="Date of Birth">
                <label class="block text-xs text-gray-500">PWD ID:</label>
                <input id="modal_pwd_id" class="w-full border rounded px-2 py-1" readonly placeholder="PWD ID">
                <label class="block text-xs text-gray-500">Disability:</label>
                <input id="type_of_disability" class="w-full border rounded px-2 py-1" readonly placeholder="Type of Disability">
                <div>
                    <label class="block text-xs text-gray-500">PWD Card:</label>
                    <img id="modal_pwd_card" class="w-16 h-16 object-cover border rounded" style="display:none;">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Profile Picture:</label>
                    <img id="modal_profile_picture" class="w-16 h-16 object-cover border rounded" style="display:none;">
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
            document.getElementById('modal_address').value = user.address;
            document.getElementById('modal_phone_number').value = user.phone_number;
            document.getElementById('modal_date_of_birth').value = user.date_of_birth;
            document.getElementById('modal_pwd_id').value = user.pwd_id;
            document.getElementById('type_of_disability').value = user.type_of_disability;
            const pwdCardImg = document.getElementById('modal_pwd_card');
            pwdCardImg.src = user.upload_pwd_card ? `/storage/${user.upload_pwd_card}` : '';
            pwdCardImg.style.display = user.upload_pwd_card ? 'block' : 'none';
            const profilePictureImg = document.getElementById('modal_profile_picture');
            profilePictureImg.src = user.profile_picture ? `/storage/${user.profile_picture}` : '';
            profilePictureImg .style.display = user.profile_picture ? 'block' : 'none';
            document.getElementById('modal_role').value = user.role;
            document.getElementById('modal_status').value = user.status;
            document.getElementById('viewProfileModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('viewProfileModal').classList.add('hidden');
        }

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteuser');
            form.action = `/EQUIJOB/Admin/Manage-User-Applicants/Delete/${userId}`;
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