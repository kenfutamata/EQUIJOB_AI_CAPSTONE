@php
$is_pdf = $is_pdf ?? false;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Generated Resume</title>
    @if(!$is_pdf)
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <style>
        body {
            font-family: sans-serif;
        }

        .resume-section {
            page-break-inside: avoid;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .resume-section h2 {
            margin-top: 0;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .resume-section h3 {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        ul {
            padding-left: 20px;
        }


        @if($is_pdf) body {
            margin: 0.5in;
            font-size: 10pt;
        }

        .resume-section {
            border-color: #e5e7eb;
        }

        h2 {
            color: #2563eb;
            font-size: 16pt;
        }

        h3 {
            font-weight: 500;
            font-size: 12pt;
        }

        img {
            border: 1px solid #ddd;
        }

        .shadow-inner {
            box-shadow: none;
        }

        .bg-gradient-to-r {
            background: #eff6ff;
        }

        @endif
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="@if(!$is_pdf) bg-gray-100 h-screen overflow-hidden @endif">

    @if(!$is_pdf)
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <aside x-show="sidebarOpen" x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-[234px] bg-white z-50 lg:hidden flex flex-col">
        <div class="flex justify-end p-4">
             <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <x-applicant-sidebar />
    </aside>

    <div class="w-[234px] bg-white hidden lg:block fixed inset-y-0 left-0 z-20">
        <x-applicant-sidebar />
    </div>

    <header class="fixed top-0 left-0 lg:left-[234px] right-0 h-16 bg-white shadow-sm z-10 flex items-center">
        <!-- Hamburger Button - visible only on mobile -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <!-- Topbar Content -->
        <div class="flex-1">
            <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
        </div>
    </header>
    @endif

    <div class="@if(!$is_pdf) pt-16 lg:ml-[234px] h-[calc(100vh-4rem)] overflow-y-auto @endif">
        <main class="@if(!$is_pdf) print-area w-full max-w-4xl p-4 md:p-6 bg-white shadow-lg rounded-lg my-6 mx-auto @endif">

            @if(!$is_pdf)
            <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">Your Generated Resume</h1>
            @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @endif

            <div class="resume-section personal-info flex @if($is_pdf) flex-row items-start @else flex-col md:flex-row md:items-start @endif justify-between @if(!$is_pdf) bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200 shadow-inner @endif">
                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
                        @if(!$is_pdf)
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endif
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 @if(!$is_pdf) sm:grid-cols-2 @endif gap-x-6 gap-y-2 text-gray-800">
                        <div><span class="font-semibold">Name:</span> <span>{{ $resume->firstName ?? '' }} {{ $resume->lastName ?? '' }}</span></div>
                        <div><span class="font-semibold">Email:</span> <span>{{ $resume->email ?? '' }}</span></div>
                        <div><span class="font-semibold">Phone:</span> <span>{{ $resume->phone ?? '' }}</span></div>
                        <div><span class="font-semibold">Address:</span> <span>{{ $resume->address ?? '' }}</span></div>
                    </div>
                    @php
                    $disabilityType = $resume->typeOfDisability ?? null;
                    $isPlaceholderDisability = (is_string($disabilityType) && strcasecmp(trim($disabilityType), "Select Disability Type") === 0);
                    @endphp
                    @if(!empty(trim((string)$disabilityType)) && !$isPlaceholderDisability)
                    <div class="mt-4 mb-2 p-3 @if(!$is_pdf) bg-blue-100 border-blue-200 @else border @endif rounded">
                        <span class="font-semibold">Disability Type:</span>
                        <span>{{ $disabilityType }}</span>
                    </div>
                    @endif
                    @if($resume->dob)
                    <div class="mt-2">
                        <span class="font-semibold">Date of Birth:</span>
                        <span>{{ \Carbon\Carbon::parse($resume->dob)->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>

                @php $photoPath = $resume->photo ?? null; @endphp
                @if($photoPath)
                <div class="flex-shrink-0 flex flex-col items-center @if(!$is_pdf) ml-0 md:ml-6 mt-8 md:mt-0 border border-gray-200 bg-white rounded p-3 shadow-sm @else ml-6 @endif" style="width: 136px;">
                    <div class="@if(!$is_pdf) p-1 bg-gray-50 rounded-md border border-gray-300 shadow-sm @endif">
                        <img src="@if($is_pdf){{ storage_path('storage/' . $photoPath) }}@else{{ asset('storage/' . $photoPath) }}@endif" alt="Applicant Photo" class="w-auto rounded-sm" style="max-height: 160px;">
                    </div>
                </div>
                @endif
            </div>

            <div class="resume-section summary">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Summary / Objective </h2>
                <div class="prose max-w-none">{!! nl2br(e($generatedSummary)) !!}</div>
            </div>

            @if($resume->experiences && $resume->experiences->count() > 0)
            <div class="resume-section experience">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Experience</h2>
                @foreach($resume->experiences as $exp)
                <div class="mb-4 pb-2 border-b border-gray-200 last:border-b-0">
                    <h3 class="text-xl font-medium">{{ $exp->jobTitle ?? 'N/A' }} at {{ $exp->employer ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">{{ $exp->location ?? '' }} ({{ $exp->year ?? '' }})</p>
                    @if($exp->description)<p class="mt-1 text-gray-700">{{ $exp->description }}</p>@endif
                </div>
                @endforeach
            </div>
            @endif

            @if($resume->educations && $resume->educations->count() > 0)
            <div class="resume-section education">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Education</h2>
                @foreach($resume->educations as $edu)
                <div class="mb-4 pb-2 border-b border-gray-200 last:border-b-0">
                    <h3 class="text-xl font-medium">{{ $edu->degree ?? 'N/A' }} - {{ $edu->school ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">{{ $edu->location ?? '' }} ({{ $edu->year ? \Carbon\Carbon::parse($edu->year)->format('Y') : '' }})</p>
                    @if($edu->description)<p class="mt-1 text-gray-700">{{ $edu->description }}</p>@endif
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($skillsList) && count($skillsList) > 0)
            <div class="resume-section skills">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Skills</h2>
                <ul class="list-disc pl-6 text-gray-800">
                    @foreach($skillsList as $skill) <li>{{ $skill }}</li> @endforeach
                </ul>
            </div>
            @endif

            @if(!$is_pdf)
            <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('applicant-resume-download') }}" class="w-full sm:w-auto text-center bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow">Download as PDF</a>
                <a href="{{ route('applicant-resume-builder') }}" class="w-full sm:w-auto text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow">Edit Resume</a>
            </div>
            @endif

        </main>
    </div>
</body>

</html>