<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Admin Manage Job Providers</title>
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
                <div class="text-4xl font-audiowide mb-6 flex items-center justify-between">
                    <div>
                        <span class="text-gray-800">Manage </span>
                        <span class="text-blue-500">Job Provider</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin-manage-user-applicants') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Applicants</a>
                        <a href="{{ route('admin-manage-user-job-providers') }}" class="bg-blue-500 text-white px-2 py-1 rounded text-base">Job Providers</a>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-gray-100 font-semibold">
                            <tr>
                                <th class="px-4 py-3">Id</th>
                                <th class="px-4 py-3">First Name</th>
                                <th class="px-4 py-3">Last Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Phone Number</th>
                                <th class="px-4 py-3">Company</th>
                                <th class="px-4 py-3">Company Logo</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                            @if($user->role == 'job_provider')
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3">{{ $user->first_name }}</td>
                                <td class="px-4 py-3">{{ $user->last_name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->phone_number }}</td>
                                <td class="px-4 py-3">{{ $user->company_name }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->company_logo)
                                    <img src="{{ asset('storage/' . $user->company_logo) }}" alt="Company Logo" class="w-[60px] h-[60px] object-cover">
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
                                        class="bg-blue-500 text-white px-2 py-1 rounded">View
                                    </button>

                                    <button class="bg-green-500 text-white px-2 py-1 rounded">Accept</button>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
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
                    <input id="modal_first_name" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                    <input id="modal_last_name" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input id="modal_email" type="email" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                    <input id="modal_phone_number" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Company Name</label>
                    <input id="modal_company_name" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                    <input id="modal_pwd_id" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Company Logo</label>
                    <img id="modal_company_logo" src="" alt="Company Logo" class="w-[100px] h-[100px] object-cover" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Role</label>
                    <input id="modal_role" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                    <input id="modal_status" type="text" class="w-full border rounded-md px-4 py-2 text-sm" />
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        function openProfileModal(button) {
            const user = JSON.parse(button.getAttribute('data-user'));

            document.getElementById('modal_first_name').value = user.first_name;
            document.getElementById('modal_last_name').value = user.last_name;
            document.getElementById('modal_email').value = user.email;
            document.getElementById('modal_phone_number').value = user.phone_number;
            document.getElementById('modal_company_name').value = user.company_name;
            const logoPath = user.company_logo ? `/storage/${user.company_logo}` : '';
            const companyLogoImg = document.getElementById('modal_company_logo');
            if (logoPath) {
                companyLogoImg.src = logoPath;
                companyLogoImg.style.display = 'block';
            } else {
                companyLogoImg.src = '';
                companyLogoImg.style.display = 'none';
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
    </script>

</body>

</html>