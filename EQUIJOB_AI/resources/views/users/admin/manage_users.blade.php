<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQUIJOB - Admin Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>

<body class="bg-white text-black">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-admin-sidebar />

        <!-- Main content area -->
        <div class="flex-1 flex flex-col w-full">
            <!-- Topbar -->
            <header class="w-full border-b border-gray-200">
                <x-topbar :user="$admin" />
            </header>

            <!-- Page content -->
            <main class="p-6">
                <div class="text-4xl font-audiowide mb-6">
                    <span class="text-gray-800">Manage </span><span class="text-blue-500">User</span>
                </div>

                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-gray-100 font-semibold">
                            <tr>
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $user->first_name }}</td>
                                    <td class="px-4 py-3">{{ $user->last_name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">{{ $user->address }}</td>
                                    <td class="px-4 py-3">{{ $user->phone_number }}</td>
                                    <td class="px-4 py-3">{{ $user->date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ $user->type_of_disability }}</td>
                                    <td class="px-4 py-3">{{ $user->pwd_id }}</td>
                                    <td class="px-4 py-3">
                                        @if ($user->pwd_card)
                                            <img src="{{ asset('storage/' . $user->pwd_card) }}" alt="PWD Card" class="w-[100px] h-[100px] object-cover">
                                        @else
                                            No Card
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $user->role }}</td>
                                    <td class="px-4 py-3">{{ $user->status }}</td>
                                    <td class="px-4 py-3">
                                        <!-- Add action buttons here -->
                                        <button class="bg-blue-500 text-white px-2 py-1 rounded">Edit</button>
                                        <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                    </td>
                                </tr>
                            @endforeach                            
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
