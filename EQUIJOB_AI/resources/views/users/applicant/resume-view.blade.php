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

            .personal-info-photo {
                margin-left: 24px !important;
                margin-top: 0 !important;
                width: 128px !important;
                height: 128px !important;
                flex-shrink: 0 !important;
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
        <div class="w-full max-w-4xl p-6 bg-white shadow-lg rounded-lg mt-6 mb-10">
            <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center no-print">Your Generated Resume</h1>

            @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <!-- Personal Info -->
            <div class="resume-section personal-info flex flex-col md:flex-row md:items-start md:justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-blue-600 mb-3">Personal Information</h2>
                    <p><strong>Name:</strong> {{ $resume->first_name ?? $resumeInstance->first_name ?? '' }} {{ $resume->last_name ?? $resumeInstance->last_name ?? '' }}</p>
                    <p><strong>Email:</strong> {{ $resume->email ?? $resumeInstance->email ?? '' }}</p>
                    <p><strong>Phone:</strong> {{ $resume->phone ?? $resumeInstance->phone ?? '' }}</p>
                    <p><strong>Address:</strong> {{ $resume->address ?? $resumeInstance->address ?? '' }}</p>
                    @if(($resume->dob ?? $resumeInstance->dob ?? false))
                    <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($resume->dob ?? $resumeInstance->dob)->format('M d, Y') }}</p>
                    @endif
                </div>
                @php $photoPath = $resume->photo ?? $resumeInstance->photo ?? null; @endphp
                @if($photoPath)
                <div class="ml-0 md:ml-6 mt-6 md:mt-0 flex-shrink-0 personal-info-photo" style="width:128px;height:128px;">
                    <img src="{{ asset('storage/' . $photoPath) }}" alt="Applicant Photo" class="w-32 h-32 object-cover rounded-md border" onerror="this.style.display='none'">
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
            @if(count($skillsList) > 0)
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
                <a href="{{ route('applicant-resume-download') }}" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow">
                    Edit Resume
                </a>
            </div>
        </div>
    </div>
</body>

</html>
