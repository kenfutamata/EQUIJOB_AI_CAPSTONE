<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Applicant Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicant_profile.css') }}">
</head>

<body class="h-screen bg-gray-100 text-gray-800 font-sans antialiased">

    <div class="fixed top-0 left-0 w-[234px] h-full z-40">
        <x-applicant-sidebar />
    </div>

    <div class="ml-[234px] flex flex-col h-screen">

        <!-- Topbar sticky - Assuming a fixed height of 64px (h-16) -->
        <div class="sticky top-0 z-30 bg-white shadow-sm flex-shrink-0 h-16">
            <x-topbar :user="$user" />
        </div>

        <!-- Title Bar sticky below topbar - Assuming a fixed height of approx 56px (h-14) for this bar -->
        <div class="sticky top-16 z-20 bg-white shadow-sm flex items-center justify-between px-6 py-4 flex-shrink-0 h-14">
            <h1 class="text-xl font-semibold">Applicant Profile</h1>
        </div>

        <!-- Scrollable main content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-10 min-h-0">
            <div class="max-w-5xl mx-auto">

                <div class="flex flex-col items-center mb-8">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/applicant/applicant-dashboard/profile_pic.png') }}"
                        alt="Profile Picture"
                        class="rounded-md w-[200px] h-[200px] object-cover mb-4 shadow-md">
                </div>

                <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">First Name</label>
                                <input type="text" value="{{ $user->first_name }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                                <input type="text" value="{{ $user->last_name }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                                <input type="text" value="{{ $user->phone_number }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Date of Birth</label>
                                <input type="date" value="{{ $user->date_of_birth }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Address</label>
                                <input type="text" value="{{ $user->address }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                                <input type="text" value="{{ $user->pwd_id }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Disability Type</label>
                                <input type="text" value="{{ $user->type_of_disability }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 md:mt-10 flex justify-center">
                        <button type="button"
                            onclick="document.getElementById('updateProfileModal').classList.remove('hidden')"
                            class="bg-white border border-gray-400 px-6 py-2 rounded-md hover:bg-gray-100 font-semibold shadow-sm text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>


     <div id="updateProfileModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <!-- Changed back to overflow-y-auto to enable vertical scrolling if content exceeds max-h -->
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <form action="{{ route('applicant-profile-update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Update Profile</h3>
                    <button type="button" onclick="closeModal()"
                        class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ $user->first_name }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ $user->last_name }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Address</label>
                        <input type="text" name="address" value="{{ $user->address }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ $user->date_of_birth }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" max="{{ date('Y-m-d') }}" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                        <input type="text" name="pwd_id" value="{{ $user->pwd_id }}"
                            pattern="\d{3}-\d{3}-\d{3}" class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload PWD Card</label>
                        <input type="file" name="upload_pwd_card"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Disability Type</label>
                        <select class="w-full border rounded-md px-4 py-2 text-sm" id="type_of_disability"
                            name="type_of_disability">
                            <option value="{{ $user->type_of_disability }}">{{ $user->type_of_disability }}</option>
                            <option disabled>Select Disability Type</option>
                            <option>Physical</option>
                            <option>Visual</option>
                            <option>Hearing</option>
                            <option>Intellectual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Profile</label>
                        <input type="file" name="profile_picture"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('updateProfileModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('updateProfileModal');
            if (e.target === modal) closeModal();
        });
    </script>
</body>

</html>