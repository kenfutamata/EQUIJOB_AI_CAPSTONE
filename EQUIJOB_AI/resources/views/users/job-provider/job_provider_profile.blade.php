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

    <aside class="hidden lg:block fixed top-0 left-0 w-[234px] h-full z-40 bg-white">
        <x-job-provider-sidebar />
    </aside>

    <div class="flex flex-col ml-0 lg:ml-[234px]">

        <header class="sticky top-0 z-30 bg-white shadow-sm flex-shrink-0 h-16">
            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </header>

        <div class="sticky top-16 z-20 bg-white shadow-sm flex items-center justify-between px-6 py-4 flex-shrink-0 h-14">
            <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                <span class="text-[#25324B]">Job Provider </span>
                <span class="text-[#26A4FF]">Profile</span>
            </h1>
        </div>

        <main class="p-6 md:p-10">
            @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

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

                        <!-- Left Column: Personal Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">First Name</label>
                                <input type="text" value="{{ $user->firstName }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                                <input type="text" value="{{ $user->lastName }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                                <input type="text" value="{{ $user->phoneNumber }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                        </div>

                        <!-- Right Column: Company Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Company Name</label>
                                <input type="text" value="{{ $user->companyName }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Company Address</label>
                                <input type="text" value="{{ $user->companyAddress }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Province</label>
                                <input type="text" value="{{ $user->province?->provinceName }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">City</label>
                                <input type="text" value="{{ $user->city?->cityName }}" readonly
                                    class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" />
                            </div>

                            <!-- Grouped Logo and Permit into their own flex container for alignment -->
                            <div class="flex items-start space-x-8 pt-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Company Logo</label>
                                    @php
                                    $logoUrl = Str::startsWith($user->companyLogo, 'http')
                                    ? $user->companyLogo
                                    : ($user->companyLogo ? "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage/companyLogo/{$user->companyLogo}" : null);
                                    @endphp
                                    <img src="{{ $logoUrl ?: asset('assets/photos/landing_page/equijob_logo.png') }}"
                                        alt="Company Logo"
                                        class="rounded-md w-[100px] h-[100px] object-cover shadow-md">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Business Permit</label>
                                    <div id="view_businessPermit" class="mt-1"></div>
                                </div>
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

    <!-- Update Modal -->
    <div id="updateProfileModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <form action="{{ route('job-provider-profile-update') }}" method="POST" enctype="multipart/form-data"
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
                        <input type="text" name="firstName" value="{{ old('firstName', $user->firstName) }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                        <input type="text" name="lastName" value="{{ old('lastName', $user->lastName) }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Company Name</label>
                        <input type="text" name="companyName" value="{{ old('companyName', $user->companyName) }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Company Address</label>
                        <input type="text" name="companyAddress" value="{{ old('companyAddress', $user->companyAddress) }}"
                            class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Province</label>
                        <select id="province" name="provinceId" class="w-full border rounded-md px-4 py-2 text-sm" required>
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('provinceId') == $province->id ? 'selected' : '' }}>
                                {{ $province->provinceName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">City</label>
                        <select id="city" name="cityId" class="w-full border rounded-md px-4 py-2 text-sm" required>
                            <option value="">Select City</option>
                            @if(old('provinceId') && $cities ?? false)
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('cityId') == $city->id ? 'selected' : '' }}>
                                {{ $city->cityName }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Company Logo</label>
                        <input type="file" name="companyLogo" class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Business Permit</label>
                        <input type="file" name="businessPermit" class="w-full border rounded-md px-4 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Profile Picture</label>
                        <input type="file" name="profilePicture" class="w-full border rounded-md px-4 py-2 text-sm" />
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
        const SUPABASE_BASE_URL = "https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage";

        const businessPermitContainer = document.getElementById('view_businessPermit');
        businessPermitContainer.innerHTML = '';
        if (userData.businessPermit) {
            const filePath = userData.businessPermit.startsWith('http') ?
                userData.businessPermit :
                `${SUPABASE_BASE_URL}/businessPermit/${userData.businessPermit}`;

            const fileExtension = filePath.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                businessPermitContainer.innerHTML = `<img src="${filePath}" alt="Business Permit" class="rounded-md w-[100px] h-[100px] object-cover shadow-md">`;
            } else if (fileExtension === 'pdf') {
                businessPermitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-600 hover:underline">View Permit (PDF)</a>`;
            } else {
                businessPermitContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-600 hover:underline">Download Permit</a>`;
            }
        } else {
            businessPermitContainer.innerHTML = `<span class="text-sm text-gray-500">Not provided</span>`;
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

        document.getElementById('province').addEventListener('change', async function() {
            const provinceId = this.value;
            const citySelect = document.getElementById('city');

            if (!provinceId) {
                citySelect.innerHTML = '<option value="">Select a Province</option>';
                return;
            }

            citySelect.innerHTML = '<option value="">Loading...</option>';

            try {
                const response = await fetch(`/cities/${provinceId}`);

                const cities = await response.json();

                citySelect.innerHTML = '<option value="">Select City</option>';

                if (cities && cities.length > 0) {
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.cityName;
                        citySelect.appendChild(option);
                    });
                } else {
                    citySelect.innerHTML = '<option value="">No cities found</option>';
                }
            } catch (err) {
                console.error('Error fetching cities:', err);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            }
        });
    </script>
</body>

</html>