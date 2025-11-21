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

    {{-- Error Messages --}}
    @if (session('error'))
    <div class="auto-fade-alert transition-opacity duration-500 ease-in-out mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-md" role="alert">
        {{ session('error') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="auto-fade-alert transition-opacity duration-500 ease-in-out mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 fixed top-2 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-lg shadow-lg" role="alert">
        <div class="flex items-center">
            <svg aria-hidden="true" class="w-5 h-5 mr-2 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
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
        {{-- Sidebar --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>
        <aside x-show="sidebarOpen" x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-[234px] bg-white z-40 lg:hidden shadow-lg flex flex-col">
            <div class="flex flex-col h-full bg-[#c7d4f8]">
                <div class="flex justify-end p-4"><button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button></div>
                <div class="flex-1 overflow-y-auto min-h-0"><x-applicant-sidebar /></div>
            </div>
        </aside>
        <aside class="hidden lg:flex lg:flex-col lg:w-[234px] lg:fixed lg:inset-y-0 lg:z-20">
            <div class="flex flex-col h-full bg-[#c7d4f8]"><div class="flex-1 overflow-y-auto min-h-0"><x-applicant-sidebar /></div></div>
        </aside>

        <div class="flex flex-col flex-1 lg:ml-[234px] h-full">
            {{-- Topbar --}}
            <header class="fixed top-0 left-0 right-0 lg:left-[234px] h-16 z-10 bg-white border-b border-gray-200 flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none"><span class="sr-only">Open sidebar</span><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                <div class="flex-1"><x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" /></div>
            </header>

            <main class="mt-16 h-[calc(100vh-4rem)] overflow-y-auto p-4 md:p-6 space-y-8">
                <form method="POST" action="{{route('applicant-resume-builder-store')}}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div class="text-3xl font-bold text-blue-800">Smart Resume <span class="text-sky-500">Builder</span></div>

                    {{-- Personal Information --}}
                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label for="firstName" class="block text-lg">First Name</label><input type="text" id="firstName" name="resume[firstName]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.firstName', $resume->firstName ?? $user->firstName) }}" readonly /></div>
                            <div><label for="lastName" class="block text-lg">Last Name</label><input type="text" id="lastName" name="resume[lastName]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.lastName', $resume->lastName ?? $user->lastName) }}" readonly /></div>
                            <div><label for="dob" class="block text-lg">Date of Birth</label><input type="date" id="dob" name="resume[dob]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.dob', $resume->dob ?? $user->dateOfBirth) }}" readonly /></div>
                            <div><label for="address" class="block text-lg">Address</label><input type="text" id="address" name="resume[address]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.address', $resume->address ?? $user->address) }}" readonly /></div>
                            <div><label for="email" class="block text-lg">Email Address</label><input type="email" id="email" name="resume[email]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.email', $resume->email ?? $user->email) }}" readonly /></div>
                            <div><label for="phone" class="block text-lg">Phone Number</label><input type="tel" id="phone" name="resume[phone]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ old('resume.phone', $resume->phone ?? $user->phoneNumber) }}" readonly /></div>
                            <div><label for="typeOfDisability" class="block text-lg">Disability Type</label><select id="typeOfDisability" name="resume[typeOfDisability]" class="w-full border border-black bg-gray-300 h-11 px-3"> @php $selectedDisability = old('resume.typeOfDisability', $resume->typeOfDisability ?? $user->typeOfDisability ?? ''); @endphp <option value="" disabled {{ $selectedDisability == '' ? 'selected' : '' }}>Select Disability Type</option> @foreach($disabilityTypes as $disability) <option value="{{ $disability }}" {{ $selectedDisability == $disability ? 'selected' : '' }}>{{ $disability }}</option> @endforeach </select></div>
                            <div><label class="block text-lg">{{ $resume && $resume->photo ? 'Change 2x2 Photo' : 'Upload a 2x2 Photo' }}</label><div x-data="{ photoPreview: '{{ $resume->photo ?? '' }}' }" class="mt-2"><input type="file" id="photo" name="resume[photo]" class="hidden" x-ref="photoInput" @change=" const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photoInput.files[0]);" accept="image/*"><div @click="$refs.photoInput.click()" class="cursor-pointer group"><template x-if="photoPreview"><div class="relative w-24 h-24"><img :src="photoPreview" alt="Current Photo" class="w-24 h-24 object-cover rounded-md border-2 border-gray-300 group-hover:border-sky-500 transition-all"><div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center transition-all"><p class="text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity text-center">Click to change</p></div></div></template><template x-if="!photoPreview"><div class="w-24 h-24 bg-gray-200 border-2 border-dashed rounded-md flex items-center justify-center text-center text-gray-500 text-xs p-2 hover:bg-gray-300 hover:border-sky-500 transition-all">Click to<br>upload photo</div></template></div><p class="text-xs text-gray-500 mt-1">A new photo is required.</p></div></div>
                            <div class="md:col-span-2"><label for="summary" class="block text-lg">About me</label><textarea id="summary" name="resume[summary]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" required>{{ old('resume.summary', $resume->summary ?? '') }}</textarea></div>
                        </div>
                    </section>
                    
                    {{-- Suggested Experiences --}}
                    @if(isset($suggestedExperiences) && !$suggestedExperiences->isEmpty())
                    <section id="suggestedExperienceSection" class="bg-blue-50 p-6 shadow rounded-lg border border-blue-200">
                        <h2 class="text-2xl font-semibold mb-2">Add Your Past Hired Roles</h2>
                        <p class="text-sm text-gray-600 mb-4">We found these roles from your application history. Click to add them to your experience.</p>
                        <div class="space-y-3">
                            @foreach($suggestedExperiences as $app)
                                @if($app->jobPosting)
                                <div class="suggestion-item bg-white p-3 rounded-md shadow-sm flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $app->jobPosting->position }}</p>
                                        <p class="text-sm text-gray-500">{{ $app->jobPosting->companyName }}</p>
                                    </div>
                                    <button type="button" class="add-suggested-exp-btn bg-sky-500 text-white px-3 py-1 rounded-md hover:bg-sky-600 text-sm transition-colors" data-job-title="{{ $app->jobPosting->position }}" data-employer="{{ $app->jobPosting->companyName }}" data-location="{{ $app->jobPosting->companyAddress }}" data-year="{{ $app->year_hired ?? '' }}">+ Add</button>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                    @endif
                    
                    {{-- Experience Section --}}
                    <section id="experienceSection" class="bg-white p-6 shadow rounded-lg">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                            <h2 class="text-2xl font-semibold">Experience</h2>
                            <button type="button" id="addExperienceBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm self-start sm:self-auto">+ Add Experience</button>
                        </div>
                        <div id="experienceEntriesContainer" class="space-y-6">
                            @if(old('experience'))
                                @foreach(old('experience') as $key => $exp)
                                <div class="experience-entry border border-gray-300 p-4 rounded-md relative"><button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label for="experience_{{$key}}_employer" class="block text-lg">Employer</label><input type="text" id="experience_{{$key}}_employer" name="experience[{{$key}}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['employer'] ?? '' }}" required /></div><div><label for="experience_{{$key}}_jobTitle" class="block text-lg">Job Title</label><input type="text" id="experience_{{$key}}_jobTitle" name="experience[{{$key}}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['jobTitle'] ?? '' }}" required /></div><div><label for="experience_{{$key}}_location" class="block text-lg">Location</label><input type="text" id="experience_{{$key}}_location" name="experience[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['location'] ?? '' }}" /></div><div><label for="experience_{{$key}}_year" class="block text-lg">Year</label><input type="text" id="experience_{{$key}}_year" name="experience[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp['year'] ?? '' }}" /></div><div class="md:col-span-2"><label for="experience_{{$key}}_responsibilities" class="block text-lg">Responsibilities/Description</label><textarea id="experience_{{$key}}_responsibilities" name="experience[{{$key}}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements...">{{ $exp['responsibilities'] ?? '' }}</textarea></div></div></div>
                                @endforeach
                            @elseif($resume && $resume->experiences->isNotEmpty())
                                @foreach($resume->experiences as $key => $exp)
                                <div class="experience-entry border border-gray-300 p-4 rounded-md relative"><button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label for="experience_{{$key}}_employer" class="block text-lg">Employer</label><input type="text" id="experience_{{$key}}_employer" name="experience[{{$key}}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp->employer }}" required /></div><div><label for="experience_{{$key}}_jobTitle" class="block text-lg">Job Title</label><input type="text" id="experience_{{$key}}_jobTitle" name="experience[{{$key}}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp->jobTitle }}" required /></div><div><label for="experience_{{$key}}_location" class="block text-lg">Location</label><input type="text" id="experience_{{$key}}_location" name="experience[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp->location }}" /></div><div><label for="experience_{{$key}}_year" class="block text-lg">Year</label><input type="text" id="experience_{{$key}}_year" name="experience[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $exp->year }}" /></div><div class="md:col-span-2"><label for="experience_{{$key}}_responsibilities" class="block text-lg">Responsibilities/Description</label><textarea id="experience_{{$key}}_responsibilities" name="experience[{{$key}}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements...">{{ $exp->description }}</textarea></div></div></div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                    
                    {{-- Education Section --}}
                    <section class="bg-white p-6 shadow rounded-lg">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                            <h2 class="text-2xl font-semibold">Education</h2>
                            <!-- THE FIRST FIX: Give this button a unique ID -->
                            <button type="button" id="addEducationBtn" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600 text-sm self-start sm:self-auto">+ Add Education</button>
                        </div>
                        <div id="educationEntriesContainer" class="space-y-6">
                           @if(old('educations'))
                            @foreach(old('educations') as $key => $edu)
                            <div class="education-entry border border-gray-300 p-4 rounded-md relative"><button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label for="education_{{$key}}_school" class="block text-lg">School</label><input type="text" id="education_{{$key}}_school" name="educations[{{$key}}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['school'] ?? '' }}" required /></div><div><label for="education_{{$key}}_degree" class="block text-lg">Degree</label><input type="text" id="education_{{$key}}_degree" name="educations[{{$key}}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['degree'] ?? '' }}" required /></div><div><label for="education_{{$key}}_location" class="block text-lg">Location</label><input type="text" id="education_{{$key}}_location" name="educations[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['location'] ?? '' }}" /></div><div><label for="education_{{$key}}_year" class="block text-lg">Year Graduated</label><input type="date" id="education_{{$key}}_year" name="educations[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu['year'] ?? '' }}" /></div><div class="md:col-span-2"><label for="education_{{$key}}_description" class="block text-lg">Description</label><textarea id="education_{{$key}}_description" name="educations[{{$key}}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info...">{{ $edu['description'] ?? '' }}</textarea></div></div></div>
                            @endforeach
                            @elseif($resume && $resume->educations->isNotEmpty())
                            @foreach($resume->educations as $key => $edu)
                            <div class="education-entry border border-gray-300 p-4 rounded-md relative"><button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label for="education_{{$key}}_school" class="block text-lg">School</label><input type="text" id="education_{{$key}}_school" name="educations[{{$key}}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu->school }}" required /></div><div><label for="education_{{$key}}_degree" class="block text-lg">Degree</label><input type="text" id="education_{{$key}}_degree" name="educations[{{$key}}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu->degree }}" required /></div><div><label for="education_{{$key}}_location" class="block text-lg">Location</label><input type="text" id="education_{{$key}}_location" name="educations[{{$key}}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu->location }}" /></div><div><label for="education_{{$key}}_year" class="block text-lg">Year Graduated</label><input type="date" id="education_{{$key}}_year" name="educations[{{$key}}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="{{ $edu->year }}" /></div><div class="md:col-span-2"><label for="education_{{$key}}_description" class="block text-lg">Description</label><textarea id="education_{{$key}}_description" name="educations[{{$key}}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info...">{{ $edu->description }}</textarea></div></div></div>
                            @endforeach
                            @endif
                        </div>
                    </section>

                    {{-- Skills Section --}}
                    <section class="bg-white p-6 shadow rounded-lg">
                        <h2 class="text-2xl font-semibold mb-4">Skills</h2>
                        <div class="mb-6"><h3 class="text-xl font-semibold mb-3 text-gray-700">AI Suggested Skills</h3><p class="text-sm text-gray-500 mb-4">Click a skill to add it to your resume. These were extracted from your uploaded certificates.</p>@if(!empty($certificates))<div class="flex flex-wrap items-center gap-2">@foreach($certificates as $cert)@if(!empty($cert['skill_name']))<button type="button" class="ai-skill-btn bg-sky-100 text-sky-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-sky-200 cursor-pointer transition-colors duration-200" data-skill="{{ trim($cert['skill_name']) }}" title="Issued by: {{ $cert['issuer'] ?? 'N/A' }} on {{ $cert['issue_date'] ?? 'N/A' }}">+ {{ $cert['skill_name'] }}</button>@endif @endforeach</div>@else<div class="text-center py-6 px-4 border-2 border-dashed rounded-lg"><p class="text-gray-500">No AI-suggested skills found.</p><a href="{{ route('applicant-profile') }}" class="text-sm text-blue-600 hover:underline mt-2 inline-block">Go to your profile and upload certificates to enable this feature.</a></div>@endif</div>
                        <div><label for="skills" class="block text-sm text-gray-600 mb-1">Enter any additional skills below, separated by commas.</label><textarea id="skills" name="skills" class="w-full border border-black bg-gray-300 h-40 resize-none p-2" placeholder="e.g., HTML, CSS, JavaScript, Python, Communication, Problem Solving...">{{ old('skills', $resume->skills ?? '') }}</textarea></div>
                    </section>

                    {{-- Submit Button --}}
                    <div class="flex justify-center py-6">
                        <button type="submit" class="bg-blue-600 text-white text-lg px-8 py-3 rounded-full hover:bg-blue-700 transition">{{ $resume ? 'Update & Regenerate Resume' : 'Generate Resume' }}</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    {{-- Initial Counts for JS --}}
    <script>
        const initialCounts = {
            experience: {{ old('experience') ? count(old('experience')) : ($resume && $resume->experiences ? $resume->experiences->count() : 0) }},
            education: {{ old('educations') ? count(old('educations')) : ($resume && $resume->educations ? $resume->educations->count() : 0) }}
        };
    </script>
    
    {{-- CONSOLIDATED JAVASCRIPT BLOCK (CORRECTED) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- EXPERIENCE BLOCKS LOGIC ---
            let experienceEntryCount = initialCounts.experience;
            const expContainer = document.getElementById('experienceEntriesContainer');
            const addExperienceBtn = document.getElementById('addExperienceBtn');
            
            addExperienceBtn.addEventListener('click', () => {
                const index = experienceEntryCount++;
                const newExperienceHtml = `
                <div class="experience-entry border border-gray-300 p-4 rounded-md relative">
                    <button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label for="experience_${index}_employer" class="block text-lg">Employer</label><input type="text" id="experience_${index}_employer" name="experience[${index}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" required /></div>
                        <div><label for="experience_${index}_jobTitle" class="block text-lg">Job Title</label><input type="text" id="experience_${index}_jobTitle" name="experience[${index}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" required /></div>
                        <div><label for="experience_${index}_location" class="block text-lg">Location</label><input type="text" id="experience_${index}_location" name="experience[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" /></div>
                        <div><label for="experience_${index}_year" class="block text-lg">Year</label><input type="text" id="experience_${index}_year" name="experience[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" /></div>
                        <div class="md:col-span-2"><label for="experience_${index}_responsibilities" class="block text-lg">Responsibilities/Description</label><textarea id="experience_${index}_responsibilities" name="experience[${index}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements..."></textarea></div>
                    </div>
                </div>`;
                expContainer.insertAdjacentHTML('beforeend', newExperienceHtml);
            });

            expContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-experience-btn')) {
                    event.target.closest('.experience-entry')?.remove();
                }
            });

            // --- EDUCATION BLOCKS LOGIC ---
            let educationEntryCount = initialCounts.education;
            const eduContainer = document.getElementById('educationEntriesContainer');
            // THE SECOND FIX: Get the correct button by its unique ID
            const addEducationBtn = document.getElementById('addEducationBtn');

            addEducationBtn.addEventListener('click', () => {
                const index = educationEntryCount++;
                const newEducationHtml = `
                <div class="education-entry border border-gray-300 p-4 rounded-md relative">
                    <button type="button" class="remove-education-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this education">×</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label for="education_${index}_school" class="block text-lg">School</label><input type="text" id="education_${index}_school" name="educations[${index}][school]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" required /></div>
                        <div><label for="education_${index}_degree" class="block text-lg">Degree</label><input type="text" id="education_${index}_degree" name="educations[${index}][degree]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" required /></div>
                        <div><label for="education_${index}_location" class="block text-lg">Location</label><input type="text" id="education_${index}_location" name="educations[${index}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" /></div>
                        <div><label for="education_${index}_year" class="block text-lg">Year Graduated</label><input type="date" id="education_${index}_year" name="educations[${index}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="" /></div>
                        <div class="md:col-span-2"><label for="education_${index}_description" class="block text-lg">Description</label><textarea id="education_${index}_description" name="educations[${index}][description]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Details about your studies, honors, or relevant info..."></textarea></div>
                    </div>
                </div>`;
                eduContainer.insertAdjacentHTML('beforeend', newEducationHtml);
            });

            eduContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-education-btn')) {
                    event.target.closest('.education-entry')?.remove();
                }
            });

            // --- AI SKILL BUTTON LOGIC ---
            const skillsTextarea = document.getElementById('skills');
            const aiSkillButtons = document.querySelectorAll('.ai-skill-btn');
            aiSkillButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const skillToAdd = this.dataset.skill;
                    const currentSkills = skillsTextarea.value.split(',').map(s => s.trim()).filter(Boolean);
                    const skillExists = currentSkills.some(s => s.toLowerCase() === skillToAdd.toLowerCase());
                    if (!skillExists) {
                        skillsTextarea.value += (skillsTextarea.value.trim() ? ', ' : '') + skillToAdd;
                    }
                    this.disabled = true;
                    this.classList.remove('bg-sky-100', 'hover:bg-sky-200', 'text-sky-800');
                    this.classList.add('bg-green-100', 'text-green-800', 'cursor-not-allowed');
                    this.textContent = '✔ Added';
                });
            });

            // --- SUGGESTED EXPERIENCES LOGIC ---
            document.querySelectorAll('.add-suggested-exp-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const jobTitle = this.dataset.jobTitle;
                    const employer = this.dataset.employer;
                    const location = this.dataset.location;
                    const year = this.dataset.year;
                    
                    let newIndex = experienceEntryCount++;
                    
                    const newExperienceHtml = `
                    <div class="experience-entry border border-gray-300 p-4 rounded-md relative">
                        <button type="button" class="remove-experience-btn absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-xl p-1" title="Remove this experience">×</button>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label for="experience_${newIndex}_employer" class="block text-lg">Employer</label><input type="text" id="experience_${newIndex}_employer" name="experience[${newIndex}][employer]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${employer}" required /></div>
                            <div><label for="experience_${newIndex}_jobTitle" class="block text-lg">Job Title</label><input type="text" id="experience_${newIndex}_jobTitle" name="experience[${newIndex}][jobTitle]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${jobTitle}" required /></div>
                            <div><label for="experience_${newIndex}_location" class="block text-lg">Location</label><input type="text" id="experience_${newIndex}_location" name="experience[${newIndex}][location]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${location}" /></div>
                            <div><label for="experience_${newIndex}_year" class="block text-lg">Year</label><input type="text" id="experience_${newIndex}_year" name="experience[${newIndex}][year]" class="w-full border border-black bg-gray-300 h-11 px-3" value="${year}" /></div>
                            <div class="md:col-span-2"><label for="experience_${newIndex}_responsibilities" class="block text-lg">Responsibilities/Description</label><textarea id="experience_${newIndex}_responsibilities" name="experience[${newIndex}][responsibilities]" class="w-full border border-black bg-gray-300 h-24 resize-none p-2" placeholder="Describe your key responsibilities and achievements..."></textarea></div>
                        </div>
                    </div>`;
                    expContainer.insertAdjacentHTML('beforeend', newExperienceHtml);
                    this.disabled = true;
                    this.closest('.suggestion-item').remove();
                    if (document.querySelectorAll('.suggestion-item').length === 0) {
                        const section = document.getElementById('suggestedExperienceSection');
                        if (section) section.style.display = 'none';
                    }
                });
            });

            // --- AUTO FADE ALERTS ---
            const alerts = document.querySelectorAll('.auto-fade-alert');
            alerts.forEach(alertEl => {
                setTimeout(() => {
                    alertEl.classList.add('opacity-0');
                    setTimeout(() => {
                        alertEl.style.display = 'none';
                    }, 500);
                }, 10000);
            });
        });
    </script>
</body>

</html>