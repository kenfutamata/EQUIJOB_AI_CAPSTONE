<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Job Applicant- Manage Job Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/applicant/applicant-manage-job-applications/css/manage_job_applications.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
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

<body class="bg-white text-black">
    <div>
        <!-- Sidebar -->
        <div class="fixed top-0 left-0 w-[234px] h-full z-40" style="background-color: #c3d2f7;">
            <x-applicant-sidebar />
        </div>

        <!-- Topbar -->
        <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </div>

        <!-- Main Content -->
        <main class="main-content-scroll bg-gray-50">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Success') }}
            </div>
            @elseif(session('error'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Applications" class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
            </div>

            <!-- Job Postings Table -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-2 py-2">Applicant Number {!! sortArrow('jobApplicationNumber')!!}</th>
                            <th class="px-2 py-2">Position {!! sortArrow('position')!!}</th>
                            <th class="px-2 py-2">Company Name{!! sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Applicant Name</th>
                            <th class="px-2 py-2">Applicant Phone Number</th>
                            <th class="px-2 py-2">Applicant Address</th>
                            <th class="px-2 py-2">Sex</th>
                            <th class="px-2 py-2">Applicant Disability Type</th>
                            <th class="px-2 py-2">Status{!! sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($applications as $application)
                        @php
                        $posting = $application->jobPosting;
                        $applicant = $application->applicant;
                        $modalData = array_merge($posting->toArray(), [
                        'firstName' => $applicant->first_name ?? null,
                        'lastName' => $applicant->last_name ?? null,
                        'sex' => $applicant->gender,
                        'contactPhone' => $applicant->phone_number,
                        'disabilityType' => $applicant->type_of_disability,
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'remarks' => $application->remarks,
                        'interviewDate' => $application->interviewDate ? $application->interviewDate->format('F j, Y') : null,
                        'interviewTime' => $application->interviewTime ? $application->interviewTime->format('g:i A') : null,
                        'interviewLink' => $application->interviewLink,
                        'profile_picture' => $applicant->profile_picture ?? null,
                        ]);
                        @endphp
                        <tr>
                            <td class="px-2 py-2">{{ $application->jobApplicationNumber ?? $application->id }}</td>
                            <td class="px-2 py-2">{{ $posting->position ?? '' }}</td>
                            <td class="px-2 py-2">{{ $posting->companyName ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->first_name ?? '' }} {{ $applicant->last_name ?? ''}}</td>
                            <td class="px-2 py-2">{{ $applicant->phone_number ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->address?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->gender ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->type_of_disability ?? ''}}</td>
                            <td class="px-2 py-2">{{ $application->status ?? ''}}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if ($application->status === 'Pending')
                                <button onclick="openViewJobApplicationModal(this)" data-application='@json($modalData)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <button onclick="openWithdrawModal(this)" data-url="{{route('applicant-manage-job-applications.withdraw', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded">Withdraw </button>
                                @elseif($application->status === 'For Interview')
                                <button onclick="openViewJobApplicationModal(this)" data-application='@json($modalData)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <button onclick="openWithdrawModal(this)" data-url="{{route('applicant-manage-job-applications.withdraw', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded">Withdraw </button>
                                @elseif($application->status === 'On-Offer')
                                <button onclick="openViewJobApplicationModal(this)" data-application='@json($modalData)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                </button>
                                <form action="{{route('applicant-job-application-hired', $application->id)}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button href="" class="bg-green-500 text-white px-2 py-1 rounded"> Accept </button>
                                </form>
                                <button onclick="openWithdrawModal(this)" data-url="{{route('applicant-manage-job-applications.withdraw', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded">Withdraw </button>
                                @else
                                <button onclick="openViewJobApplicationModal(this)" data-application='@json($modalData)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-center">
                    {!! $applications->links('pagination::tailwind') !!}
                </div>
            </div>
        </main>
    </div>
    <!-- View Job Applications Modal -->
    <div id="viewJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
        <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

            <button onclick="closeviewJobApplicationModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl">&times;</button>

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
                            <p id="modal.contactEmail" class="text-sm text-blue-600 font-medium">₱ 0</p>
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