<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Generated Resume</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: sans-serif;
        }

        .resume-section {
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

        @media print {
            .no-print {
                display: none !important;
            }

            .personal-info {
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                display: flex !important;
            }

            .personal-info-photo-container {
                margin-left: 24px !important;
                margin-top: 0 !important;
                width: 128px !important; /* Container width for the photo */
                height: auto !important; /* Allow space for caption */
                flex-shrink: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
            }
            .personal-info-photo-container img { /* Target the image directly for print sizing */
                width: 128px !important;
                height: 128px !important;
                object-fit: cover !important;
            }
            .personal-info-photo-wrapper { /* Remove screen styling for print */
                padding: 0 !important;
                border: none !important;
                background: none !important;
                box-shadow: none !important;
            }
            .personal-info-photo-caption {
                font-size: 8pt !important; /* Adjust caption size for print */
                margin-top: 2px !important;
                text-align: center !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-[234px] bg-white hidden lg:block fixed inset-y-0 left-0 z-40">
        <x-applicant-sidebar />
    </div>

    <!-- Topbar -->
    <div class="fixed top-0 left-0 lg:left-[234px] right-0 h-16 bg-white shadow-sm z-30">
        <x-topbar :user="$user" />
    </div>

    <!-- Main Content Wrapper -->
    <div class="pt-16 lg:ml-[234px] h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="w-full max-w-4xl p-6 bg-white shadow-lg rounded-lg mt-6 mb-10 mx-auto">
            <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center no-print">Your Generated Resume</h1>

            @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <!-- Personal Info -->
            <div class="resume-section personal-info flex flex-col md:flex-row md:items-start md:justify-between bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200 shadow-inner">
                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-gray-800">
                        <div>
                            <span class="font-semibold">Name:</span>
                            <span>{{ $resume->first_name ?? $resumeInstance->first_name ?? '' }} {{ $resume->last_name ?? $resumeInstance->last_name ?? '' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Email:</span>
                            <span>{{ $resume->email ?? $resumeInstance->email ?? '' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Phone:</span>
                            <span>{{ $resume->phone ?? $resumeInstance->phone ?? '' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Address:</span>
                            <span>{{ $resume->address ?? $resumeInstance->address ?? '' }}</span>
                        </div>
                        {{-- Enhanced Disability Section --}}
                        @php
                            $disabilityType = $resume->disability_type ?? $resumeInstance->disability_type ?? null;
                            $isPlaceholderDisability = false;
                            if (is_string($disabilityType) && strcasecmp(trim($disabilityType), "Select Disability Type") === 0) {
                                $isPlaceholderDisability = true;
                            }
                        @endphp
                    </div>
                    {{-- Separate Disability Type visually --}}
                    @if(!empty(trim((string)$disabilityType)) && !$isPlaceholderDisability)
                    <div class="mt-4 mb-2 p-3 bg-blue-100 border border-blue-200 rounded">
                        <span class="font-semibold">Disability Type:</span>
                        <span>{{ $disabilityType }}</span>
                    </div>
                    @endif
                    @if(($resume->dob ?? $resumeInstance->dob ?? false))
                    <div class="mt-2">
                        <span class="font-semibold">Date of Birth:</span>
                        <span>{{ \Carbon\Carbon::parse($resume->dob ?? $resumeInstance->dob)->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
                {{-- Separate Photo Section visually --}}
                @php $photoPath = $resume->photo ?? $resumeInstance->photo ?? null; @endphp
                @if($photoPath)
                <div class="ml-0 md:ml-6 mt-8 md:mt-0 flex-shrink-0 personal-info-photo-container flex flex-col items-center border border-gray-200 bg-white rounded p-3 shadow-sm" style="width: 128px;">
                    <div class="personal-info-photo-wrapper p-1 bg-gray-50 rounded-md border border-gray-300 shadow-sm">
                        <img src="{{ asset('storage/' . $photoPath) }}"
                             alt="Applicant Photo"
                             id="applicantPhoto"
                             class="w-32 h-32 object-cover rounded-sm"
                             onerror="this.style.display='none'; if(document.getElementById('photoCaption')) { document.getElementById('photoCaption').style.display='none'; }">
                    </div>
                    <span id="photoCaption" class="personal-info-photo-caption text-xs text-gray-600 mt-1 italic">
                        2x2 Photo
                    </span>
                </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="resume-section summary">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Summary / Objective </h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($generatedSummary)) !!}
                </div>
            </div>

            <!-- Experience -->
            @if($resume->experiences && $resume->experiences->count() > 0)
            <div class="resume-section experience">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Experience</h2>
                @foreach($resume->experiences as $exp)
                <div class="mb-4 pb-2 border-b border-gray-200 last:border-b-0">
                    <h3 class="text-xl font-medium">{{ $exp->job_title ?? 'N/A' }} at {{ $exp->employer ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">{{ $exp->location ?? '' }} ({{ $exp->year ?? '' }})</p>
                    @if($exp->description)
                    <p class="mt-1 text-gray-700">{{ $exp->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- Education -->
            @if($resume->educations && $resume->educations->count() > 0)
            <div class="resume-section education">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Education</h2>
                @foreach($resume->educations as $edu)
                <div class="mb-4 pb-2 border-b border-gray-200 last:border-b-0">
                    <h3 class="text-xl font-medium">{{ $edu->degree ?? 'N/A' }} - {{ $edu->school ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">{{ $edu->location ?? '' }} ({{ $edu->year ? \Carbon\Carbon::parse($edu->year)->format('Y') : '' }})</p>
                    @if($edu->description)
                    <p class="mt-1 text-gray-700">{{ $edu->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- Skills -->
            @if(isset($skillsList) && count($skillsList) > 0)
            <div class="resume-section skills">
                <h2 class="text-2xl font-semibold text-blue-600 mb-3">Skills</h2>
                <ul class="list-disc pl-6 text-gray-800">
                    @foreach($skillsList as $skill)
                    <li>{{ $skill }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-8 text-center no-print">
                <button onclick="window.print()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow">
                    Print / Save as PDF
                </button>
                <a href="{{ route('applicant-resume-builder') }}" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow">
                    Edit Resume
                </a>
            </div>
        </div>
    </div>
</body>

</html>