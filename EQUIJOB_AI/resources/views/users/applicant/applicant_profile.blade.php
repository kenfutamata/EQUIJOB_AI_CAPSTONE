<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{sidebarOpen:false}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Applicant Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('assets/applicant/applicant-profile/js/applicant_profile.js') }}"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="min-h-screen bg-gray-100 text-gray-800 font-sans antialiased">

    <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
        <x-applicant-sidebar />
    </aside>

    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-[234px] bg-white z-40 lg:hidden shadow-lg flex flex-col overflow-y-auto">
        <div class="flex flex-col h-full bg-[#c7d4f8]">

            <div class="flex justify-end p-4 bg-[#c7d4f8]">
                <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <x-applicant-sidebar />
            </div>

        </div>
    </aside>

    <div class="flex flex-col ml-0 lg:ml-[234px] ">

        <div class="sticky top-0 z-20 bg-white shadow-sm flex-shrink-0 h-16 flex items-center justify-between px-4 sm:px-6">
            <button type="button" @click="sidebarOpen = true" class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex-1"></div>

            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </div>


        <div class="sticky top-16 z-10 bg-white shadow-sm flex items-center justify-between px-6 py-4 flex-shrink-0 h-14">
            <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                <span class="text-[#25324B]">Applicant </span>
                <span class="text-[#26A4FF]">Profile</span>
            </h1>
        </div>

        <main class="p-6 md:p-10">
            <div class="max-w-5xl mx-auto">

                <div class="flex flex-col items-center mb-8">
                    @php
                    $profileUrl = Str::startsWith($user->profilePicture, 'http')
                    ? $user->profilePicture
                    : ($user->profilePicture ? "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage/profilePicture/{$user->profilePicture}" : null);
                    @endphp
                    <img src="{{ $profileUrl ?: asset('assets/applicant/applicant-dashboard/profile_pic.png') }}"
                        alt="Profile Picture"
                        class="rounded-md w-[200px] h-[200px] object-cover mb-4 shadow-md">
                </div>

                <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">First Name</label>
                                <input type="text" value="{{ $user->firstName }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                                <input type="text" value="{{ $user->lastName }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                                <input type="text" value="{{ $user->phoneNumber }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Date of Birth</label>
                                <input type="date" value="{{ $user->dateOfBirth }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Address</label>
                                <input type="text" value="{{ $user->address }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                                <input type="text" value="{{ $user->pwdId }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Disability Type</label>
                                <input type="text" value="{{ $user->typeOfDisability }}" disabled
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 md:mt-10 flex justify-center">
                        <button type="button"
                            onclick="openModal()"
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
                        <input type="text" name="firstName" value="{{ $user->firstName }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                        <input type="text" name="lastName" value="{{ $user->lastName }}"
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
                        <input type="date" name="dateOfBirth" value="{{ $user->dateOfBirth }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" max="{{ date('Y-m-d') }}" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">PWD ID</label>
                        <input type="text" name="pwdId" value="{{ $user->pwdId }}"
                            pattern="\d{2}-\d{4}-\d{3}-\d{7}" class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload PWD Card</label>
                        <input type="file" name="upload_pwd_card"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Disability Type</label>
                        <select class="w-full border rounded-md px-4 py-2 text-sm" id="typeOfDisability"
                            name="typeOfDisability">
                            <option selected disabled>Select Disability Type</option>
                            <option value="Deaf or Hard of Hearing">Deaf or Hard of Hearing</option>
                            <option value="Intellectual Disability">Intellectual Disability</option>
                            <option value="Learning Disability">Learning Disability</option>
                            <option value="Mental Disability">Mental Disability</option>
                            <option value="Physical Disability (Orthopedic)">Physical Disability (Orthopedic)</option>
                            <option value="Psychosocial Disability">Psychosocial Disability</option>
                            <option value="Speech and Language Impairment">Speech and Language Impairment</option>
                            <option value="Visual Disability">Visual Disability</option>
                            <option value="Cancer (RA11215)">Cancer (RA11215)</option>
                            <option value="Rare Disease (RA10747)">Rare Disease (RA10747)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Profile</label>
                        <input type="file" name="profilePicture"
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


</body>

</html>