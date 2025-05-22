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
    <div class="flex min-h-screen">
        <x-admin-sidebar />

        <div class="flex-1 flex flex-col w-full">
            <header class="w-full border-b border-gray-200">
                <x-topbar :user="$admin" />
            </header>

            <main class="p-6">
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
                    <form method="GET" action="{{ route('admin-manage-user-applicants') }}" class="mb-4 flex justify-end">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search applicants"
                            class="border rounded-l px-2 py-1 w-32 text-sm focus:outline-none" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-gray-100 font-semibold">
                            <tr>
                                <th class="px-4 py-3">Id</th>
                                <th class="px-4 py-3">First Name</th>
                                <th class="px-4 py-3">Last Name</th>
                                <th class="px-4 py-3">Email Address</th>
                                <th class="px-4 py-3">Full Address</th>
                                <th class="px-4 py-3">Phone Number</th>
                                <th class="px-4 py-3">Date of Birth</th>
                                <th class="px-4 py-3">Type of Disability</th>
                                <th class="px-4 py-3">PWD ID</th>
                                <th class="px-4 py-3">PWD Card</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                            @if($user->role == 'applicant')
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3">{{ $user->first_name }}</td>
                                <td class="px-4 py-3">{{ $user->last_name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->address }}</td>
                                <td class="px-4 py-3">{{ $user->phone_number }}</td>
                                <td class="px-4 py-3">{{ $user->date_of_birth }}</td>
                                <td class="px-4 py-3">{{ $user->type_of_disability }}</td>
                                <td class="px-4 py-3">{{ $user->pwd_id }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->upload_pwd_card)
                                    <img src="{{ asset('storage/' . $user->upload_pwd_card) }}" alt="PWD Card" class="w-[100px] h-[100px] object-cover">
                                    @else
                                    No Card
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $user->role }}</td>
                                <td class="px-4 py-3">{{ $user->status }}</td>
                                <td class="px-4 py-3">
                                    <button type="button"
                                        onclick="openProfileModal(this)"
                                        data-user='@json($user)'
                                        class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                    @if($user->status == 'inactive')
                                    <form action="{{ route('admin-manage-user-applicants-accept', $user->id) }}" method="POST" style="display:inline;">
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
    </div>

    <div id="viewProfileModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Profile</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">First Name</label>
                    <input id="modal_first_name" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                    <input id="modal_last_name" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input id="modal_email" type="email" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Address</label>
                    <input id="modal_address" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                    <input id="modal_phone_number" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Date of Birth</label>
                    <input id="modal_date_of_birth" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                    <input id="modal_pwd_id" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">PWD Card</label>
                    <img id="modal_pwd_card" src="" alt="PWD Card" class="w-[100px] h-[100px] object-cover" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Disability Type</label>
                    <input id="type_of_disability" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Role</label>
                    <input id="modal_role" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                    <input id="modal_status" type="text" class="w-full border rounded-md px-4 py-2 text-sm" disabled />
                </div>
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
                <button type="submit" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Yes</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">No</button>
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
            if (user.upload_pwd_card) {
                pwdCardImg.src = "{{ asset('storage') }}/" + user.upload_pwd_card;
                pwdCardImg.style.display = 'block';
            } else {
                pwdCardImg.src = '';
                pwdCardImg.style.display = 'none';
            }
            document.getElementById('modal_role').value = user.role;
            document.getElementById('modal_status').value = user.status;
            document.getElementById('viewProfileModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('viewProfileModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('viewProfileModal');
            if (e.target === modal) closeModal();
        });
        setTimeout(function() {
            var notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 2500);
        setTimeout(function() {
            var notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteuser');
            form.action = `/EQUIJOB/Admin/Manage-User-Applicants/Delete/${userId}`;
            document.getElementById('DeleteRoleModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteRoleModal').classList.add('hidden');
        }
    </script>
</body>

</html>