    <!DOCTYPE html>
    <html lang="en" class="h-full">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>EQUIJOB - Job Provider Profile</title>
        <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jobprovider_profile.css') }}">
    </head>

    <body class="min-h-screen bg-gray-100 text-gray-800 font-sans antialiased">

        <div class="fixed top-0 left-0 w-[234px] h-full z-40 bg-white">
            <x-job-provider-sidebar />
        </div>

        <div class="flex flex-col ml-0 lg:ml-[234px] ">

            <div class="sticky top-0 z-30 bg-white shadow-sm flex-shrink-0 h-16">
                <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
            </div>

            <div class="sticky top-16 z-20 bg-white shadow-sm flex items-center justify-between px-6 py-4 flex-shrink-0 h-14">
                <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                    <span class="text-[#25324B]">Job Provider </span>
                    <span class="text-[#26A4FF]">Profile</span>
                </h1>
            </div>

            <main class="p-6 md:p-10">
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
                                    <input type="text" value="{{ $user->first_name }}" readonly
                                        class="w-full border rounded-md px-4 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                                    <input type="text" value="{{ $user->last_name }}" readonly
                                        class="w-full border rounded-md px-4 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                                    <input type="email" value="{{ $user->email }}" readonly
                                        class="w-full border rounded-md px-4 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                                    <input type="text" value="{{ $user->phone_number }}" readonly
                                        class="w-full border rounded-md px-4 py-2 text-sm" />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Company Name</label>
                                    <input type="text" value="{{ $user->company_name }}" readonly
                                        class="w-full border rounded-md px-4 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Company Logo</label>
                                    <img src="{{ $user->company_logo ? asset('storage/' . $user->company_logo) : asset('assets/applicant/applicant-dashboard/profile_pic.png') }}"
                                        alt="Company Logo"
                                        class="rounded-md w-[100px] h-[100px] object-cover mb-4 shadow-md">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Business Permit</label>
                                    <div id="view_business_permit" class="mt-2"></div>

                                </div>
                            </div>
                        </div>

                        <div class="mt-8 md:mt-10 flex justify-center">
                            <button type="button"
                                onclick="openModal()"
                                class="bg-white border border-gray-400 px-6 py-2 rounded-md hover:bg-gray-100 font-semibold shadow-sm text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Information
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div id="updateProfileModal"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
                <form action="{{ route('job-provider-profile-update', $user->id) }}" method="POST" enctype="multipart/form-data"
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
                            <label class="block text-sm text-gray-600 mb-1">Company Name</label>
                            <input type="text" name="company_name" value="{{ $user->company_name }}"
                                class="w-full border rounded-md px-4 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Upload Company Logo</label>
                            <input type="file" name="company_logo"
                                class="w-full border rounded-md px-4 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Upload Business Permit</label>
                            <input type="file" name="business_permit"
                                class="w-full border rounded-md px-4 py-2 text-sm" />
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
            const userData = @json($user);
            const businessPermitContainer = document.getElementById('view_business_permit');

            if (userData && userData.business_permit) {
                const permitPath = userData.business_permit;
                const filePath = `/storage/${permitPath}`;

                const extension = permitPath.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'webp'].includes(extension)) {
                    businessPermitContainer.innerHTML = `<a href="${filePath}" target="_blank" title="Click to view full size"><img src="${filePath}" class="w-24 h-24 object-cover rounded-md border shadow-sm" alt="Business Permit Preview"/></a>`;
                } else if (extension === 'pdf') {
                    businessPermitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-600 underline hover:text-blue-800">View Business Permit (PDF)</a>`;
                } else {
                    businessPermitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-600 underline hover:text-blue-800">Download Business Permit</a>`;
                }
            } else {
                businessPermitContainer.innerHTML = `<p class="text-sm text-gray-500">No Business Permit uploaded.</p>`;
            }

            function openModal() {
                document.getElementById('updateProfileModal').classList.remove('hidden');
            }

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