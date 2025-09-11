<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EQUIJOB - Job Recommendations</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Epilogue:wght@400;600;700&family=Inter:wght@400&display=swap" rel="stylesheet">
    <!-- Added Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('assets/applicant/job_recommendations.js') }}" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Epilogue', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                        audiowide: ['Audiowide', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body x-data="{ sidebarOpen: false }" class="bg-[#FCFDFF] text-gray-800 font-sans antialiased min-h-screen flex">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <!-- Mobile Sidebar -->
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

    <aside class="w-[234px] bg-white hidden lg:block h-screen fixed top-0 left-0 z-20">
        <x-applicant-sidebar />
    </aside>

    <div class="flex flex-col flex-1 w-full lg:ml-[234px] min-h-screen">
        <header class="fixed top-0 left-0 lg:left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200 flex items-center">
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

        <!-- Notification Alerts -->
        @if(session('Success'))
        <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
            {{ session('Success') }}
        </div>
        @elseif(session('error'))
        <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
            {{ session('error') }}
        </div>
        @endif

        <main class="p-4 sm:p-6 lg:p-10 pt-20 lg:pt-24 flex-1">
            <div class="mb-8">
                <h1 class="font-audiowide text-3xl md:text-4xl text-gray-800">
                    <span class="text-[#25324B]">AI Job </span>
                    <span class="text-[#26A4FF]">Matching</span>
                </h1>
                <p class="text-gray-500 mt-2">Personalized job recommendations just for you, based on your resume.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">

                @forelse($recommendedJobs as $job)
                <article class="bg-white border border-gray-200 rounded-lg p-6 flex flex-col gap-4 hover:shadow-lg hover:border-blue-500 transition-all duration-300">
                    <header class="flex justify-between items-center gap-4 flex-wrap">
                        @if($job->companyLogo)
                        <img class="w-12 h-12 rounded-lg object-contain border border-gray-100" src="{{ asset('storage/' . $job->companyLogo) }}" alt="{{ $job->companyName }} logo" />
                        @else
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg">
                            {{ substr($job->companyName, 0, 1) }}
                        </div>
                        @endif

                        <div class="ml-auto flex flex-col sm:flex-row gap-2">
                            <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($job)'
                                class="text-center text-sm font-semibold text-blue-600 border border-blue-600 px-4 py-1.5 rounded-full whitespace-nowrap hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                View Job Posting
                            </button>

                            <button onclick="openApplyJobModal(this)" data-jobposting='@json($job)'
                                class="text-center text-sm font-semibold text-green-600 border border-green-600 px-4 py-1.5 rounded-full whitespace-nowrap hover:bg-green-600 hover:text-white transition-colors duration-200">
                                Apply Now
                            </button>
                        </div>
                    </header>

                    <div class="flex flex-col gap-2">
                        <div>
                            <h3 class="text-lg font-bold text-[#25324B]">{{ $job->position }}</h3>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mt-1 flex-wrap">
                                <span>{{ $job->companyName }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 font-inter text-sm line-clamp-2">
                        {{ $job->description }}
                    </p>
                    <footer class="mt-auto pt-2">
                        @if($job->disabilityType && $job->disabilityType !== 'Any' && $job->disabilityType !== 'Not Specified')
                        <span class="text-sm font-semibold text-yellow-800 bg-yellow-100 px-3 py-1 rounded-full">
                            {{ $job->disabilityType }}
                        </span>
                        @endif
                    </footer>
                </article>
                @empty
                <div class="col-span-full bg-blue-50 border border-blue-200 text-blue-800 p-8 rounded-lg text-center">
                    <h3 class="text-xl font-bold">No Matching Jobs Found</h3>
                    <p class="mt-2">We couldn't find any jobs that strongly match your current resume.</p>
                    <p class="mt-1">Consider updating your skills in the Resume Builder for better results.</p>
                    <a href="{{ route('applicant-resume-builder') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Go to Resume Builder
                    </a>
                </div>
                @endforelse
            </div>
        </main>
    </div>

    <div id="viewJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
        <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

            <button onclick="closeViewJobPostingModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl z-10">&times;</button>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img id="modal-companyLogo" src="https://placehold.co/180x151" alt="Company Logo" class="w-44 h-auto border" style="display:block;" />
                    <div id="modal-companyInitial" class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg" style="display:none;"></div>
                </div>
                <div>
                    <h2 id="modal-companyName" class="text-2xl font-semibold text-gray-800">Company Name</h2>
                    <p id="modal-position" class="text-xl text-gray-700 mt-1">Position</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold border-b pb-1">Job Information</h3>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/disabled.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Disability Type</p>
                            <p id="modal-disabilityType" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/salary.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Salary</p>
                            <p id="modal-salaryRange" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/phone.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Contact Number</p>
                            <p id="modal-contactPhone" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/workplace.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Work Environment</p>
                            <p id="modal-workEnvironment" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/email.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Email Address</p>
                            <p id="modal-contactEmail" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex flex-col gap-6">
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Job Description</h3>
                        <p id="modal-description" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Educational Attainment</h3>
                        <p id="modal-educationalAttainment" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Skills</h3>
                        <p id="modal-skills" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Requirements</h3>
                        <p id="modal-requirements" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="applyJobModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeApplyJobModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Job Application</h2>
            <form method="POST" action="{{route('applicant-job-application-store')}}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div>
                    <label class="block text-xs text-gray-500">First Name</label>
                    <input name="firstName" class="w-full border rounded px-2 py-1" readonly value="{{ $user->first_name }}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Last Name</label>
                    <input name="lastName" class="w-full border rounded px-2 py-1" readonly value="{{ $user->last_name }}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Position</label>
                    <input name="position" id="apply_position" class="w-full border rounded px-2 py-1" readonly>
                    @error('position')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Company Name</label>
                    <input name="companyName" id="apply_companyName" class="w-full border rounded px-2 py-1" readonly>
                    @error('companyName')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Sex</label>
                    <input type="text" name="sex" class="w-full border rounded px-2 py-1" readonly value="{{ $user->gender}}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Home Address</label>
                    <input type="text" name="address" class="w-full border rounded px-2 py-1" readonly value="{{ $user->address }}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Disability Type</label>
                    <input type="text" name="disabilityType" class="w-full border rounded px-2 py-1" readonly value="{{ $user->type_of_disability}}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Contact Phone</label>
                    <input name="contactPhone" class="w-full border rounded px-2 py-1" value="{{$user->phone_number}}" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Contact Email</label>
                    <input type="email" name="contactEmail" class="w-full border rounded px-2 py-1" value="{{$user->email}}" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Upload Resume</label>
                    <input type="file" name="uploadResume" id="uploadResume" accept="image/*, application/pdf " class="w-full border rounded px-2 py-1" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Upload Application Letter</label>
                    <input type="file" name="uploadApplicationLetter" accept="image/*, application/pdf " class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeApplyJobModal()" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Apply</button>
                </div>
                <input type="hidden" name="jobPostingID" id="apply_jobPostingID">
                <input type="hidden" name="jobProviderID" id="apply_jobProviderID">
            </form>
        </div>
    </div>
</body>

</html>