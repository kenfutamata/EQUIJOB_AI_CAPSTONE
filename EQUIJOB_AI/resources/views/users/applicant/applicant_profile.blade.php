<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{sidebarOpen:false}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Applicant Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <script src="{{ asset('assets/applicant/applicant-profile/js/applicant_profile.js') }}"></script> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-gray-100 text-gray-800 font-sans antialiased">

    {{-- Sidebar and Topbar code remains the same --}}
    <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
        <x-applicant-sidebar />
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <aside x-show="sidebarOpen" x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-[234px] bg-white z-40 lg:hidden shadow-lg flex flex-col overflow-y-auto">
        <div class="flex flex-col h-full bg-[#c7d4f8]">
            <div class="flex justify-end p-4 bg-[#c7d4f8]">
                <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <x-applicant-sidebar />
            </div>
        </div>
    </aside>

    <div class="flex flex-col ml-0 lg:ml-[234px] ">
        @if(session('Success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('Success') }}
        </div>
        @elseif(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
        @endif
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
                    <img src="{{ $profileUrl ?: asset('assets/applicant/applicant-dashboard/profile_pic.png') }}" alt="Profile Picture" class="rounded-md w-[200px] h-[200px] object-cover mb-4 shadow-md">
                </div>
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-4">
                            <div><label class="block text-sm text-gray-600 mb-1">First Name</label><input type="text" value="{{ $user->firstName }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Last Name</label><input type="text" value="{{ $user->lastName }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Email Address</label><input type="email" value="{{ $user->email }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Phone Number</label><input type="text" value="{{ $user->phoneNumber }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">PWD ID</label><input type="text" value="{{ $user->pwdId }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                        </div>
                        <div class="space-y-4">
                            <div><label class="block text-sm text-gray-600 mb-1">Date of Birth</label><input type="date" value="{{ $user->dateOfBirth }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Address</label><input type="text" value="{{ $user->address }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Province</label><input type="text" value="{{ $user->province?->provinceName }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">City</label><input type="text" value="{{ $user->city?->cityName }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Disability Type</label><input type="text" value="{{ $user->typeOfDisability }}" disabled class="w-full border rounded-md px-4 py-2 text-sm bg-gray-50 cursor-not-allowed" /></div>
                            <div> <label class="block text-sm font-medium text-gray-700 mb-2">Skills Certificates</label>
                                @if($user->certificates && count($user->certificates) > 0)
                                <p class="text-xs text-gray-600 mb-2">Currently uploaded files:</p>
                                <div class="space-y-2 border rounded-md p-3 mb-4 bg-gray-50">
                                    @foreach($user->certificates as $certificateUrl)
                                    <div id="cert-{{ $loop->index }}" class="flex items-center justify-between text-sm">
                                        <a href="{{ $certificateUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center text-indigo-600 hover:underline truncate pr-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                            </svg><span class="truncate">{{ basename($certificateUrl) }}</span></a>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-xs text-gray-500 mb-4">No certificates have been uploaded yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-10 flex justify-center">
                        <button type="button" onclick="openModal()" class="bg-white border border-gray-400 px-6 py-2 rounded-md hover:bg-gray-100 font-semibold shadow-sm text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL --}}
    <div id="updateProfileModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <form action="{{ route('applicant-profile-update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Update Profile</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>
                <div class="space-y-4">
                    {{-- Form fields... --}}
                    <div><label class="block text-sm text-gray-600 mb-1">First Name</label><input type="text" name="firstName" value="{{ old('firstName', $user->firstName) }}" class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />@error('firstName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Last Name</label><input type="text" name="lastName" value="{{ old('lastName', $user->lastName) }}" class="w-full border rounded-md px-4 py-2 text-sm" pattern="[A-Za-z\s]+" />@error('lastName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Email Address</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-md px-4 py-2 text-sm" />@error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Phone Number</label><input type="text" name="phoneNumber" value="{{ old('phoneNumber', $user->phoneNumber) }}" class="w-full border rounded-md px-4 py-2 text-sm" />@error('phoneNumber') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Address</label><input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border rounded-md px-4 py-2 text-sm" />@error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
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
                    <div><label class="block text-sm text-gray-600 mb-1">Date of Birth</label><input type="date" name="dateOfBirth" value="{{ old('dateOfBirth', $user->dateOfBirth) }}" class="w-full border rounded-md px-4 py-2 text-sm" max="{{ date('Y-m-d') }}" />@error('dateOfBirth') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">PWD ID</label><input type="text" name="pwdId" value="{{ old('pwdId', $user->pwdId) }}" placeholder="XX-XXXX-XXX-XXXXXXX" pattern="\d{2}-\d{4}-\d{3}-\d{7}" class="w-full border rounded-md px-4 py-2 text-sm" />@error('pwdId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Upload PWD Card</label><input type="file" name="upload_pwd_card" class="w-full border rounded-md px-4 py-2 text-sm" />@error('upload_pwd_card') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Disability Type</label><select class="w-full border rounded-md px-4 py-2 text-sm" id="typeOfDisability" name="typeOfDisability">
                            <option value="" disabled>Select Disability Type</option>@php $disabilityTypes = ["Deaf or Hard of Hearing", "Intellectual Disability", "Learning Disability", "Mental Disability", "Physical Disability (Orthopedic)", "Psychosocial Disability", "Speech and Language Impairment", "Visual Disability", "Cancer (RA11215)", "Rare Disease (RA10747)"]; @endphp @foreach($disabilityTypes as $type)<option value="{{ $type }}" {{ old('typeOfDisability', $user->typeOfDisability) == $type ? 'selected' : '' }}>{{ $type }}</option>@endforeach
                        </select>@error('typeOfDisability') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm text-gray-600 mb-1">Upload Profile Picture</label><input type="file" name="profilePicture" class="w-full border rounded-md px-4 py-2 text-sm" />@error('profilePicture') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skills Certificates</label>
                        @if($user->certificates && count($user->certificates) > 0)
                        <p class="text-xs text-gray-600 mb-2">Currently uploaded files:</p>
                        <div class="space-y-2 border rounded-md p-3 mb-4 bg-gray-50">
                            @foreach($user->certificates as $certificateUrl)
                            <div id="cert-{{ $loop->index }}" class="flex items-center justify-between text-sm">
                                <a href="{{ $certificateUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center text-indigo-600 hover:underline truncate pr-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg><span class="truncate">{{ basename($certificateUrl) }}</span></a>
                                <button type="button" class="remove-existing-cert-btn ml-4 text-red-600 hover:text-red-800 flex-shrink-0 text-lg" data-url="{{ $certificateUrl }}" data-target-id="cert-{{ $loop->index }}">&times;</button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-gray-500 mb-4">No certificates have been uploaded yet.</p>
                        @endif
                        <label class="block text-sm text-gray-600 mb-1">Upload New Skills Certificates</label>
                        <div id="upload-area" class="flex justify-center w-full px-6 py-10 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-indigo-500 bg-gray-50">
                            <div class="space-y-1 text-center"><svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <p class="pl-1">Click to upload or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                            </div>
                        </div>
                        <input type="file" id="certificatesInput" name="certificates[]" multiple class="hidden" accept="image/*" />
                        <div id="certificatePreview" class="mt-4 space-y-2"></div>
                        @error('certificates') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        @error('certificates.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div id="deletions-container"></div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global modal functions
        function closeModal() {
            document.getElementById('updateProfileModal').classList.add('hidden');
        }

        function openModal() {
            document.getElementById('updateProfileModal').classList.remove('hidden');

            // --- FIX: INITIALIZE PROVINCE/CITY LOGIC ON MODAL OPEN ---
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const allCities = @json($cities ?? []);

            // This function populates the city dropdown
            function populateCities() {
                const provinceId = provinceSelect.value;
                const selectedCityId = "{{ old('cityId', $user->cityId) }}"; // Get the city ID to pre-select

                citySelect.innerHTML = '<option value="">Select City</option>'; // Clear existing options

                if (provinceId) {
                    const filteredCities = allCities.filter(city => city.province_id == provinceId);
                    filteredCities.forEach(city => {
                        const option = new Option(city.cityName, city.id);
                        if (city.id == selectedCityId) {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                }
            }

            // Remove any previous listener to avoid duplicates
            provinceSelect.removeEventListener('change', populateCities);
            // Add the event listener
            provinceSelect.addEventListener('change', populateCities);

            // Trigger it once on open to load the initial cities
            if (provinceSelect.value) {
                populateCities();
            }
        }

        // --- ALL OTHER LOGIC CAN RUN ON DOMCONTENTLOADED ---
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('certificatesInput');
            const previewContainer = document.getElementById('certificatePreview');
            let selectedFiles = [];

            function updateFileInputAndPreview() {
                previewContainer.innerHTML = '';
                if (selectedFiles.length > 0) {
                    const header = document.createElement('h4');
                    header.className = 'text-sm font-medium text-gray-700';
                    header.textContent = `Selected Files (${selectedFiles.length}):`;
                    previewContainer.appendChild(header);

                    selectedFiles.forEach((file, index) => {
                        const filePreview = document.createElement('div');
                        filePreview.className = 'flex items-center justify-between p-2 border rounded-md bg-gray-50 text-sm';
                        filePreview.innerHTML = `
                            <div class="flex items-center truncate">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                <span class="font-medium text-gray-800 truncate" title="${file.name}">${file.name}</span>
                                <span class="text-gray-500 ml-2 flex-shrink-0">(${formatBytes(file.size)})</span>
                            </div>
                            <button type="button" data-index="${index}" class="remove-file-btn ml-4 text-red-600 hover:text-red-800 flex-shrink-0">&times;</button>
                        `;
                        previewContainer.appendChild(filePreview);
                    });

                    document.querySelectorAll('.remove-file-btn').forEach(button => button.addEventListener('click', removeFile));
                }
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            function handleFiles(newFiles) {
                Array.from(newFiles).forEach(file => {
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                });
                updateFileInputAndPreview();
            }

            function removeFile(e) {
                const indexToRemove = parseInt(e.target.getAttribute('data-index'));
                selectedFiles.splice(indexToRemove, 1);
                updateFileInputAndPreview();
            }

            if (uploadArea) {
                uploadArea.addEventListener('click', () => fileInput.click());
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => uploadArea.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false));
                ['dragenter', 'dragover'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.add('border-indigo-500', 'bg-indigo-50'), false));
                ['dragleave', 'drop'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50'), false));
                uploadArea.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files), false);
            }

            fileInput.addEventListener('change', () => handleFiles(fileInput.files));

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }

            const deletionsContainer = document.getElementById('deletions-container');
            document.querySelectorAll('.remove-existing-cert-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const certificateUrl = this.dataset.url;
                    const targetId = this.dataset.targetId;
                    const certificateElement = document.getElementById(targetId);

                    if (certificateElement) {
                        certificateElement.style.display = 'none';
                    }

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'certificates_to_delete[]';
                    hiddenInput.value = certificateUrl;

                    if (deletionsContainer) {
                        deletionsContainer.appendChild(hiddenInput);
                    }
                });
            });


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
                const response = await fetch(`/applicant/applicant-profile/cities/${provinceId}`);

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