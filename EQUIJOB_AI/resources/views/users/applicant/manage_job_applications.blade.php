<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Manage Job Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Added Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="{{ asset('assets/applicant/applicant-manage-job-applications/css/manage_job_applications.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

@php
function sortArrow($column) {
$currentSort = request('sort');
$direction = request('direction') === 'asc' ? 'desc' : 'asc';
$arrow = request('sort') === $column
? (request('direction') === 'asc' ? '↑' : '↓')
: '↕';
$params = array_merge(request()->all(), ['sort' => $column, 'direction' => $direction]);
$url = request()->url() . '?' . http_build_query($params);
return "<a href=\"$url\" class=\"text-xs\">$arrow</a>";
}
@endphp

<body x-data="{ sidebarOpen: false }" class="bg-gray-50 text-black flex min-h-screen">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <!-- Mobile Sidebar -->
    <aside x-show="sidebarOpen" x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-[234px] z-50 lg:hidden flex flex-col" style="background-color: #c3d2f7;">
        <div class="flex justify-end p-4">
            <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <x-applicant-sidebar />
    </aside>

    <!-- Static Desktop Sidebar -->
    <aside class="hidden lg:block fixed top-0 left-0 w-[234px] h-full z-20" style="background-color: #c3d2f7;">
        <x-applicant-sidebar />
    </aside>

    <!-- Main content wrapper -->
    <div class="flex flex-col flex-1 w-full lg:ml-[234px]">
        <!-- Topbar -->
        <header class="fixed top-0 left-0 lg:left-[234px] right-0 h-16 z-10 bg-white border-b border-gray-200 flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex-1">
                <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
            </div>
        </header>

        <!-- Main Content -->
        <main class="mt-16 p-4 sm:p-6 flex-1">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-20 sm:top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
                {{ session('Success') }}
            </div>
            @elseif(session('error'))
            <div id="notification-bar" class="fixed top-20 sm:top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 w-11/12 max-w-lg text-center">
                {{ session('error') }}
            </div>
            @endif

            <!-- Page Header -->
            <div class="text-3xl font-semibold mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                <div>
                    <span class="text-gray-800">Manage </span>
                    <span class="text-blue-500">Job Applications</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="" class="flex items-center gap-1 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
            </div>

            <!-- Job Applications Table -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-left sm:text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Application Number {!! sortArrow('jobApplicationNumber')!!}</th>
                            <th class="px-3 py-2">Position {!! sortArrow('position')!!}</th>
                            <th class="px-3 py-2">Company Name {!! sortArrow('companyName')!!}</th>
                            <th class="px-3 py-2 hidden md:table-cell">Applicant First Name {!! sortArrow('firstName')!!}</th>
                            <th class="px-3 py-2 hidden md:table-cell">Applicant Last Name {!! sortArrow('lastName')!!}</th>
                            <th class="px-3 py-2 hidden lg:table-cell">Phone Number {!! sortArrow('phoneNumber')!!}</th>
                            <th class="px-3 py-2 hidden lg:table-cell">Disability Type {!! sortArrow('disabilityType')!!}</th>
                            <th class="px-3 py-2">Status{!! sortArrow('status')!!}</th>
                            <th class="px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($applications as $application)
                        @php
                        $posting = $application->jobPosting;
                        $applicant = $application->applicant;
                        // Ensure objects exist before accessing properties
                        $modalData = array_merge($posting ? $posting->toArray() : [], [
                        'firstName' => $applicant->firstName ?? null,
                        'lastName' => $applicant->lastName ?? null,
                        'sex' => $applicant->gender ?? null,
                        'contactPhone' => $applicant->phoneNumber ?? null,
                        'contactEmail' => $applicant->email ?? null,
                        'disabilityType' => $applicant->typeOfDisability ?? null,
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'remarks' => $application->remarks,
                        'interviewDate' => $application->interviewDate ? $application->interviewDate->format('F j, Y') : null,
                        'interviewTime' => $application->interviewTime ? $application->interviewTime->format('g:i A') : null,
                        'interviewLink' => $application->interviewLink,
                        'profilePicture' => $applicant->profilePicture ?? null,
                        ]);
                        @endphp
                        <tr>
                            <td class="px-3 py-2">{{ $applications->firstItem() + $loop->index }}</td>
                            <td class="px-3 py-2">{{ $application->jobApplicationNumber ?? $application->id }}</td>
                            <td class="px-3 py-2">{{ $posting->position ?? 'N/A' }}</td>
                            <td class="px-3 py-2">{{ $posting->companyName ?? 'N/A' }}</td>
                            <td class="px-3 py-2 hidden md:table-cell">{{ $applicant->firstName ?? '' }}</td>
                            <td class="px-3 py-2 hidden md:table-cell">{{ $applicant->lastName ?? '' }}</td>
                            <td class="px-3 py-2 hidden lg:table-cell">{{ $applicant->phoneNumber ?? 'N/A' }}</td>
                            <td class="px-3 py-2 hidden lg:table-cell">{{ $applicant->typeOfDisability ?? 'N/A'}}</td>
                            <td class="px-3 py-2">{{ $application->status ?? 'N/A'}}</td>
                            <td class="px-3 py-2 space-y-1">
                                <button onclick="openViewJobApplicationModal(this)" data-application='@json($modalData)' class="bg-blue-500 text-white px-2 py-1 rounded w-full sm:w-auto">View</button>
                                @if (in_array($application->status, ['Pending', 'For Interview', 'On-Offer']))
                                <button onclick="openWithdrawModal(this)" data-url="{{route('applicant-manage-job-applications.withdraw', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded w-full sm:w-auto">Withdraw</button>
                                @endif
                                @if ($application->status === 'On-Offer')
                                <form action="{{route('applicant-job-application-hired', $application->id)}}" method="POST" class="inline-block w-full sm:w-auto">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded w-full">Accept</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No job applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($applications->hasPages())
                <div class="p-4">
                    {!! $applications->links('pagination::tailwind') !!}
                </div>
                @endif
            </div>
        </main>
    </div>

    <div id="viewJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
        <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

            <button onclick="closeviewJobApplicationModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl z-10">&times;</button>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img id="modal.applicantProfile" src="https://placehold.co/180x151" alt="Applicant Profile" class="w-44 h-auto border" style="display:block;" />
                    <div id="modal.applicantInitial" class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg" style="display:none;"></div>
                </div>
                <div>
                    <h2 id="modal.applicantName" class="text-2xl font-semibold text-gray-800">Applicant's Name</h2>
                    <p id="modal.position" class="text-xl text-gray-700 mt-1">Position</p>
                    <p id="modal.companyName" class="text-xl text-gray-700 mt-1">Company Name</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold border-b pb-1">Applicant Information</h3>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/disabled.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Disability Type</p>
                            <p id="modal.disabilityType" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/salary.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Sex</p>
                            <p id="modal.sex" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/phone.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Contact Number</p>
                            <p id="modal.contactPhone" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/email.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Email Address</p>
                            <p id="modal.contactEmail" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/workplace.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Resume</p>
                            <div id="modal_view_resume"></div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/photos/job-applicant/job-recommendations/workplace.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Application Letter</p>
                            <div id="modal_view_application_letter"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex flex-col gap-6">
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Interview Date</h3>
                        <p id="modal.interviewDate" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Interview Time</h3>
                        <p id="modal.interviewTime" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Google Meet Link</h3>
                        <p id="modal.interviewLink" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Remarks</h3>
                        <p id="modal.remarks" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="withdrawJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Withdrawal Application</h3>
                <button onclick="closeWithdrawModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            <form id="withdrawForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="remarks-input" class="block text-sm font-medium text-gray-700 mb-1">Please state your reason for withdrawing the application.</label>
                    <textarea id="remarks-input" name="remarks" rows="4" class="w-full border rounded px-2 py-1" required></textarea>
                </div>
                <button type="submit" class="w-full py-2 px-4 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">Submit Withdrawal</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/applicant/applicant-manage-job-applications/js/manage_job_applications.js') }}"></script>
</body>

</html>