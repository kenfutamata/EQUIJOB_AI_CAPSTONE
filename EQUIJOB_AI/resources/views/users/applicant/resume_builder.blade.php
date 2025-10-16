<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Resume Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link rel="stylesheet" href="{{ asset('assets/applicant/resume-builder/css/resume_builder.css') }}">
</head>

<body x-data="{ sidebarOpen: false }" class="bg-gray-100 text-gray-800 font-sans antialiased h-screen overflow-hidden">

    @if (session('error'))
    {{-- START: MODIFIED BLOCK 1 --}}
    <div class="auto-fade-alert transition-opacity duration-500 ease-in-out mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-md" role="alert">
        {{ session('error') }}
    </div>
    {{-- END: MODIFIED BLOCK 1 --}}
    @endif

    @if ($errors->any())
    <div class="auto-fade-alert transition-opacity duration-500 ease-in-out mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-lg shadow-lg" role="alert">
        <div class="flex items-center">
            <svg aria-hidden="true" class="w-5 h-5 mr-2 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <strong class="font-bold">Please Fix the following error displayed:</strong>
        </div>
        <ul class="mt-1.5 ml-4 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex h-full">

        <div x-show="sidebarOpen"
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
            class="fixed inset-y-0 left-0 w-[234px] bg-white z-40 lg:hidden shadow-lg flex flex-col">
            <div class="flex flex-col h-full bg-[#c7d4f8]">
                <div class="flex justify-end p-4">
                    <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto min-h-0">
                    <x-applicant-sidebar />
                </div>
            </div>
        </aside>
        <aside class="hidden lg:flex lg:flex-col lg:w-[234px] lg:fixed lg:inset-y-0 lg:z-20">
            <div class="flex flex-col h-full bg-[#c7d4f8]">
                <div class="flex-1 overflow-y-auto min-h-0">
                    <x-applicant-sidebar />
                </div>
            </div>
        </aside>

        <div class="flex flex-col flex-1 lg:ml-[234px] h-full">
            <header class="fixed top-0 left-0 right-0 lg:left-[234px] h-16 z-10 bg-white border-b border-gray-200 flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex-1">
                    <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
                </div>
            </header>

            <main class="mt-16 h-[calc(100vh-4rem)] overflow-y-auto p-4 md:p-6 space-y-8">

                <form method="POST" action="{{route('applicant-resume-builder-store')}}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div class="text-3xl font-bold text-blue-800">
                        Smart Resume <span class="text-sky-500">Builder</span>
                    </div>

                    {{-- The rest of your form is unchanged --}}
                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-lg">First Name</label>
                                <input type="text" id="firstName" name="resume[firstName]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.firstName', $user->firstName) }}" readonly />
                            </div>
                            <div>
                                <label for="lastName" class="block text-lg">Last Name</label>
                                <input type="text" id="lastName" name="resume[lastName]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.lastName', $user->lastName) }}" readonly />
                            </div>
                            <div>
                                <label for="dob" class="block text-lg">Date of Birth</label>
                                <input type="date" id="dob" name="resume[dob]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.dob', $user->dateOfBirth) }}" readonly />
                            </div>
                            <div>
                                <label for="address" class="block text-lg">Address</label>
                                <input type="text" id="address" name="resume[address]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.address', $user->address) }}" readonly />
                            </div>
                            <div>
                                <label for="email" class="block text-lg">Email Address</label>
                                <input type="email" id="email" name="resume[email]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.email', $user->email) }}" readonly />
                            </div>
                            <div>
                                <label for="phone" class="block text-lg">Phone Number</label>
                                <input type="tel" id="phone" name="resume[phone]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.phone', $user->phoneNumber) }}" readonly />
                            </div>

                            <div>
                                <label for="disability_type" class="block text-lg">Disability Type</label>
                                <select id="typeOfDisability" name="resume[typeOfDisability]" class="w-full border border-black bg-gray-300 h-11 px-3">
                                    <option value="" disabled {{ old('resume.typeOfDisability', $user->typeOfDisability ?? '') == '' ? 'selected' : '' }}>Select Disability Type</option>

                                    @foreach($disabilityTypes as $disability)
                                    <option value="{{ $disability }}" {{ old('resume.typeOfDisability', $user->typeOfDisability ?? '') == $disability ? 'selected' : '' }}>
                                        {{ $disability }}
                                    </option>
                                    @endforeach

                                </select>
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
                    
                    <section id="experienceSection" class="bg-white p-6 shadow rounded-lg">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                            <h2 class="text-2xl font-semibold">Experience</h2>
                            <button type="button" id="addExperienceBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm self-start sm:self-auto">
                                + Add Experience
                            </button>
                        </div>
                        <div id="experienceEntriesContainer" class="space-y-6">
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
                                        <label for="experience_{{$key}}_jobTitle" class="block text-lg">Job Title</label>
                                        <input type="text" id="experience_{{$key}}_jobTitle" name="experience[{{$key}}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['jobTitle'] ?? '' }}" />
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

                    <section class="bg-white p-6 shadow rounded-lg">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                            <h2 class="text-2xl font-semibold">Education</h2>
                            <button type="button" id="addEducationBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm self-start sm:self-auto">
                                + Add Education
                            </button>
                        </div>
                        <div id="educationEntriesContainer" class="space-y-6">
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

                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Skills</h2>
                        <label for="skills" class="block text-sm text-gray-600 mb-1">Enter skills separated by commas (e.g., JavaScript, Project Management, Team Leadership)</label>
                        <textarea id="skills" name="skills" class="w-full border border-black bg-gray-300 h-40 resize-none p-2" placeholder="e.g., HTML, CSS, JavaScript, Python, Communication, Problem Solving...">{{ old('skills') }}</textarea>
                    </section>

                    <div class="flex justify-center py-6">
                        <button type="submit" class="bg-blue-600 text-white text-lg px-8 py-3 rounded-full hover:bg-blue-700 transition">
                            Generate Resume
                        </button>
                    </div>

                </form>

            </main>
        </div>
    </div>
    
    <script>
        const initialCounts = {
            experience: {{ old('experience') ? count(old('experience')) : 0 }},
            education: {{ old('educations') ? count(old('educations')) : 0 }}
        };
    </script>

    <script src="{{ asset('assets/applicant/resume-builder/js/resume_builder.js') }}"></script>



</body>

</html>