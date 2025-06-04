<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Resume Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicant_profile.css') }}">
    <style>
        /* Additional styles for better UX if needed */
        .remove-experience-btn,
        .remove-education-btn {
            line-height: 1;
            /* Adjust for better vertical alignment of × */
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased h-screen overflow-hidden">

    @if (session('error'))
    <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-auto max-w-md" role="alert">
        {{ session('error') }}
    </div>
    @endif

    {{-- Display All Laravel Validation Errors --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-auto max-w-lg shadow-lg" role="alert">
            <div class="flex items-center">
                <svg aria-hidden="true" class="w-5 h-5 mr-2 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <strong class="font-bold">Please correct the following errors:</strong>
            </div>
            <ul class="mt-1.5 ml-4 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="flex h-full">

        <!-- Fixed Sidebar -->
        <div class="w-[234px] bg-white hidden lg:block fixed inset-y-0 left-0 z-40">
            <x-applicant-sidebar />
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 ml-[234px] h-full">

            <!-- Fixed Topbar -->
            <div class="fixed top-0 left-[234px] right-0 h-16 bg-white shadow-sm z-30">
                <x-topbar :user="$user" />
            </div>

            <!-- Scrollable Content -->
            <div class="mt-16 h-[calc(100vh-4rem)] overflow-y-auto p-6 space-y-8 max-w-screen-xl mx-auto">

                <form method="POST" action="{{route('applicant-resume-builder-store')}}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <!-- Title -->
                    <div class="text-3xl font-bold text-blue-800">
                        Smart Resume <span class="text-sky-500">Builder</span>
                    </div>

                    <!-- Personal Information -->
                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-lg">First Name</label>
                                <input type="text" id="first_name" name="resume[first_name]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.first_name', $user->first_name) }}" required />
                            </div>
                            <div>
                                <label for="last_name" class="block text-lg">Last Name</label>
                                <input type="text" id="last_name" name="resume[last_name]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.last_name', $user->last_name) }}" required />
                            </div>
                            <div>
                                <label for="dob" class="block text-lg">Date of Birth</label>
                                <input type="date" id="dob" name="resume[dob]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.dob', $user->date_of_birth) }}" />
                            </div>
                            <div>
                                <label for="address" class="block text-lg">Address</label>
                                <input type="text" id="address" name="resume[address]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.address', $user->address) }}" />
                            </div>
                            <div>
                                <label for="email" class="block text-lg">Email Address</label>
                                <input type="email" id="email" name="resume[email]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.email', $user->email) }}" required />
                            </div>
                            <div>
                                <label for="phone" class="block text-lg">Phone Number</label>
                                <input type="tel" id="phone" name="resume[phone]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.phone', $user->phone_number) }}" />
                            </div>
                            <div>
                                <label for="photo" class="block text-lg">Upload a 2x2 Photo</label>
                                <input type="file" id="photo" name="resume[photo]" class="w-full border border-black bg-gray-300 h-11 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="summary" class="block text-lg">Summary/Objective</label>
                                <textarea id="summary" name="resume[summary]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2">{{ old('resume.summary') }}</textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Experience -->
                    <section id="experienceSection" class="bg-white p-6 shadow rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold">Experience</h2>
                            <button type="button" id="addExperienceBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm">
                                + Add Experience
                            </button>
                        </div>
                        <div id="experienceEntriesContainer" class="space-y-6">
                            {{-- Experience entries will be added here by JavaScript --}}
                            {{-- Handle old input for dynamically added fields if needed --}}
                            @if(old('experience'))
                                @foreach(old('experience') as $key => $exp)
                                <div class="experience-entry border border-gray-300 p-4 rounded-md relative">
                                    <button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="experience_{{$key}}_employer" class="block text-lg">Employer</label>
                                            <input type="text" id="experience_{{$key}}_employer" name="experience[{{$key}}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['employer'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="experience_{{$key}}_job_title" class="block text-lg">Job Title</label>
                                            <input type="text" id="experience_{{$key}}_job_title" name="experience[{{$key}}][job_title]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['job_title'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="experience_{{$key}}_location" class="block text-lg">Location</label>
                                            <input type="text" id="experience_{{$key}}_location" name="experience[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['location'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="experience_{{$key}}_year" class="block text-lg">Year</label>
                                            <input type="text" id="experience_{{$key}}_year" name="experience[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['year'] ?? '' }}" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="experience_{{$key}}_responsibilities" class="block text-lg">Responsibilities/Description</label>
                                            <textarea id="experience_{{$key}}_responsibilities" name="experience[{{$key}}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements...">{{ $exp['responsibilities'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </section>

                    <!-- Education -->
                    <section class="bg-white p-6 shadow rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold">Education</h2>
                            <button type="button" id="addEducationBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm">
                                + Add Education
                            </button>
                        </div>
                        <div id="educationEntriesContainer" class="space-y-6">
                             {{-- Education entries will be added here by JavaScript --}}
                            {{-- Handle old input for dynamically added fields if needed --}}
                            @if(old('educations'))
                                @foreach(old('educations') as $key => $edu)
                                <div class="education-entry border border-gray-300 p-4 rounded-md relative">
                                    <button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="education_{{$key}}_school" class="block text-lg">School</label>
                                            <input type="text" id="education_{{$key}}_school" name="educations[{{$key}}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['school'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="education_{{$key}}_degree" class="block text-lg">Degree</label>
                                            <input type="text" id="education_{{$key}}_degree" name="educations[{{$key}}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['degree'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="education_{{$key}}_location" class="block text-lg">Location</label>
                                            <input type="text" id="education_{{$key}}_location" name="educations[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['location'] ?? '' }}" />
                                        </div>
                                        <div>
                                            <label for="education_{{$key}}_year" class="block text-lg">Year Graduated</label>
                                            <input type="date" id="education_{{$key}}_year" name="educations[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['year'] ?? '' }}" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="education_{{$key}}_description" class="block text-lg">Description</label>
                                            <textarea id="education_{{$key}}_description" name="educations[{{$key}}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info...">{{ $edu['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </section>

                    <!-- Skills -->
                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Skills</h2>
                        <label for="skills" class="block text-sm text-gray-600 mb-1">Enter skills separated by commas (e.g., JavaScript, Project Management, Team Leadership)</label>
                        <textarea id="skills" name="skills" class="w-full border border-black bg-gray-300 h-40 resize-none p-2" placeholder="e.g., HTML, CSS, JavaScript, Python, Communication, Problem Solving...">{{ old('skills') }}</textarea>
                    </section>

                    <!-- Generate Button -->
                    <div class="flex justify-center py-6">
                        <button type="submit" class="bg-blue-600 text-white text-lg px-8 py-3 rounded-full hover:bg-blue-700 transition">
                            Generate Resume
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let experienceEntryCount = {{ old('experience') ? count(old('experience')) : 0 }};
            let educationEntryCount = {{ old('educations') ? count(old('educations')) : 0 }};

            const experienceEntriesContainer = document.getElementById('experienceEntriesContainer');
            const addExperienceBtn = document.getElementById('addExperienceBtn');

            function createExperienceEntryHTML(index, data = {}) {
                // Use 'responsibilities' to match the name attribute below
                const responsibilities = data.responsibilities || '';
                return `
                <div class="experience-entry border border-gray-300 p-4 rounded-md relative">
                    ${index > 0 || experienceEntriesContainer.children.length > 0 ? '<hr class="mb-4 border-t border-gray-200">' : ''}
                    <button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="experience_${index}_employer" class="block text-lg">Employer</label>
                            <input type="text" id="experience_${index}_employer" name="experience[${index}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.employer || ''}" />
                        </div>
                        <div>
                            <label for="experience_${index}_job_title" class="block text-lg">Job Title</label>
                            <input type="text" id="experience_${index}_job_title" name="experience[${index}][job_title]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.job_title || ''}" />
                        </div>
                        <div>
                            <label for="experience_${index}_location" class="block text-lg">Location</label>
                            <input type="text" id="experience_${index}_location" name="experience[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.location || ''}" />
                        </div>
                        <div>
                            <label for="experience_${index}_year" class="block text-lg">Year</label>
                            <input type="text" id="experience_${index}_year" name="experience[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.year || ''}" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="experience_${index}_responsibilities" class="block text-lg">Responsibilities/Description</label>
                            <textarea id="experience_${index}_responsibilities" name="experience[${index}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements...">${responsibilities}</textarea>
                        </div>
                    </div>
                </div>
            `;
            }

            function addExperienceEntry(isInitial = false, data = {}) {
                const index = experienceEntryCount++;
                const entryHTML = createExperienceEntryHTML(index, data);
                // If handling old data, PHP already rendered it. JS adds new ones.
                // So, only insert if it's not an initial load from JS for an empty form.
                if (!isInitial && Object.keys(data).length === 0) { // Only add via JS if not pre-filled by PHP's old()
                     experienceEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);
                } else if (isInitial && experienceEntriesContainer.children.length === 0) {
                     // If it's an initial call AND the container is empty (meaning old() didn't populate it)
                     experienceEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);
                }


                if (!isInitial && experienceEntriesContainer.lastElementChild) {
                    experienceEntriesContainer.lastElementChild.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }

            addExperienceBtn.addEventListener('click', () => {
                addExperienceEntry(false); // Not initial, no data prefill
            });

            experienceEntriesContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-experience-btn')) {
                    const entryDiv = event.target.closest('.experience-entry');
                    if (entryDiv) {
                        entryDiv.remove();
                        // Optionally re-index or decrement count if strict indexing is needed on backend
                    }
                }
            });

            // Add initial empty experience entry IF no old data was populated by PHP
            if (experienceEntryCount === 0) {
                addExperienceEntry(true);
            }


            // --- Education Section ---
            const educationEntriesContainer = document.getElementById('educationEntriesContainer');
            const addEducationBtn = document.getElementById('addEducationBtn');
            // educationEntryCount is already initialized above using old('educations')

            function createEducationEntryHTML(index, data = {}) {
                return `
                <div class="education-entry border border-gray-300 p-4 rounded-md relative">
                    ${index > 0 || educationEntriesContainer.children.length > 0 ? '<hr class="mb-4 border-t border-gray-200">' : ''}
                    <button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="education_${index}_school" class="block text-lg">School</label>
                            <input type="text" id="education_${index}_school" name="educations[${index}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.school || ''}" />
                        </div>
                        <div>
                            <label for="education_${index}_degree" class="block text-lg">Degree</label>
                            <input type="text" id="education_${index}_degree" name="educations[${index}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.degree || ''}" />
                        </div>
                        <div>
                            <label for="education_${index}_location" class="block text-lg">Location</label>
                            <input type="text" id="education_${index}_location" name="educations[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.location || ''}" />
                        </div>
                        <div>
                            <label for="education_${index}_year" class="block text-lg">Year Graduated</label>
                            <input type="date" id="education_${index}_year" name="educations[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${data.year || ''}" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="education_${index}_description" class="block text-lg">Description</label>
                            <textarea id="education_${index}_description" name="educations[${index}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info...">${data.description || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
            }

            function addEducationEntry(isInitial = false, data = {}) {
                const index = educationEntryCount++;
                const entryHTML = createEducationEntryHTML(index, data);

                if (!isInitial && Object.keys(data).length === 0) {
                     educationEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);
                } else if (isInitial && educationEntriesContainer.children.length === 0) {
                     educationEntriesContainer.insertAdjacentHTML('beforeend', entryHTML);
                }


                if (!isInitial && educationEntriesContainer.lastElementChild) {
                    educationEntriesContainer.lastElementChild.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }

            addEducationBtn.addEventListener('click', () => {
                addEducationEntry(false); // Not initial, no data prefill
            });

            educationEntriesContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-education-btn')) {
                    const entryDiv = event.target.closest('.education-entry');
                    if (entryDiv) {
                        entryDiv.remove();
                    }
                }
            });

            // Add initial empty education entry IF no old data was populated by PHP
            if (educationEntryCount === 0) {
                addEducationEntry(true);
            }

        });
    </script>

</body>
</html>