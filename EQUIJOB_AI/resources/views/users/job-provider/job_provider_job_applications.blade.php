<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>EQUIJOB - Job Provider- Manage Job Applications</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <link href="{{asset('assets/job-provider/manage-job-applications/css/job_provider_job_applications.css')}}" rel="stylesheet">


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
            <x-job-provider-sidebar />
        </div>

        <!-- Topbar -->
        <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
            <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
        </div>

        <main class="main-content-scroll bg-gray-50">
            @if(session('Success'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 flex justify-center items-center">
                {{ session('Success') }}
            </div>
            @elseif(session('error'))
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 flex justify-center items-center">
                {{ session('error') }}
            </div>
            @elseif ($errors->any())
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 max-w-md w-full justify-center items-center">
                <h4 class="font-bold mb-1">Please correct the following errors:</h4>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="text-3xl font-semibold mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                <div>
                    <span class="text-gray-800">Manage </span>
                    <span class="text-blue-500">Job Applications</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('job-provider-job-applications-export') }}" class="bg-green-500 text-white px-2 py-1 rounded text-base">Export to Excel</a>

                    <form method="GET" action="" class="flex items-center gap-1 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Applications" class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Applicant Number {!! sortArrow('jobApplicationNumber')!!}</th>
                            <th class="px-2 py-2">Position {!! sortArrow('position')!!}</th>
                            <th class="px-2 py-2">Company Name {!! sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Applicant First Name {!! sortArrow('firstName')!!}</th>
                            <th class="px-2 py-2">Applicant Last Name {!! sortArrow('lastName')!!}</th>
                            <th class="px-2 py-2">Applicant Phone Number {!! sortArrow('phoneNumber')!!}</th>
                            <th class="px-2 py-2">Sex {!! sortArrow('gender')!!}</th>
                            <th class="px-2 py-2">Email Address {!! sortArrow('emailAddress')!!}</th>
                            <th class="px-2 py-2">Applicant Disability Type {!! sortArrow('disabilityType')!!}</th>
                            <th class="px-2 py-2">Status {!! sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($applications as $application)
                        @php
                        $posting = $application->jobPosting;
                        $applicant = $application->applicant;
                        $modalData = [
                        'position' => $posting->position ?? 'N/A',
                        'companyName' => $posting->companyName ?? 'N/A',
                        'firstName' => $applicant->firstName ?? 'N/A',
                        'lastName' => $applicant->lastName ?? 'N/A',
                        'gender' => $applicant->gender ?? 'N/A',
                        'age' => $applicant->age ?? 'N/A',
                        'contactPhone' => $applicant->phoneNumber ?? 'N/A',
                        'contactEmail' => $applicant->email ?? 'N/A',
                        'address' => $applicant->address ?? 'N/A',
                        'disabilityType' => $applicant->typeOfDisability ?? 'N/A',
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'interviewDate' => $application->interviewDate ? \Carbon\Carbon::parse($application->interviewDate)->format('F j, Y') : null,
                        'interviewTime' => $application->interviewTime ? \Carbon\Carbon::parse($application->interviewTime)->format('g:i A') : null,
                        'interviewLink' => $application->interviewLink,
                        'profilePicture' => $applicant->profilePicture ?? null,

                        ];
                        @endphp
                        <tr>
                            <td class="px-2 py-2">{{ $applications->firstItem() + $loop->index }}</td>
                            <td class="px-2 py-2">{{ $application->jobApplicationNumber ?? $application->id }}</td>
                            <td class="px-2 py-2">{{ $posting->position ?? '' }}</td>
                            <td class="px-2 py-2">{{ $posting->companyName ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->firstName ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->lastName ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->phoneNumber ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->gender ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->email ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->typeOfDisability ?? '' }}</td>
                            <td class="px-2 py-2">{{ $application->status ?? '' }}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if ($application->status === 'Pending')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button onclick="openCreateInterviewDetailsModal('{{ route('job-provider-manage-job-applications.scheduleinterview', $application) }}')" class="bg-green-500 text-white px-2 py-1 rounded">
                                    For Interview
                                </button>
                                <button onclick="openRejectJobApplicationModal('{{ route('job-provider-manage-job-applications.reject', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Disapprove</button>
                                <button onclick="openDeleteApplicationModal('{{ route('job-provider-manage-job-applications-delete', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                @elseif($application->status == 'For Interview')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <form action="{{route('job-provider-manage-job-applications.update-to-offer', $application->id)}}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">
                                        Offer
                                    </button>
                                </form>
                                <button onclick="openRejectJobApplicationModal('{{ route('job-provider-manage-job-applications.reject', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Disapprove</button>
                                <button onclick="openDeleteApplicationModal('{{ route('job-provider-manage-job-applications-delete', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                @elseif($application->status == 'On-Offer')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button onclick="openDeleteApplicationModal('{{ route('job-provider-manage-job-applications-delete', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                @else
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button onclick="openDeleteApplicationModal('{{ route('job-provider-manage-job-applications-delete', $application) }}')"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
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

    <div id="viewJobApplicationsModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
        <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

            <button onclick="closeViewJobApplicationsModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl">&times;</button>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img id="modal-applicantProfile" alt="Applicant Profile" class="w-44 h-auto border rounded-full" style="display:block;" />
                    <div id="modal-applicantInitial" class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg" style="display:none;"></div>
                </div>
                <div>
                    <h2 id="modal-applicantName" class="text-2xl font-semibold text-gray-800">Applicant's Name</h2>
                    <p id="modal-position" class="text-xl text-gray-700 mt-1">Position</p>
                    <p id="modal-companyName" class="text-xl text-gray-700 mt-1">Company Name</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold border-b pb-1">Applicant Information</h3>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/disability.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Disability Type</p>
                            <p id="modal-disabilityType" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/gender.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Sex</p>
                            <p id="modal-sex" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/phone.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Contact Number</p>
                            <p id="modal-contactPhone" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/email.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Email Address</p>
                            <p id="modal-contactEmail" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/address.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Applicant's Address</p>
                            <p id="modal-address" class="text-sm text-blue-600 font-medium"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/resume.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Resume</p>
                            <div id="modal_view_resume"></div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/job-provider/manage-job-applications/pictures/applicationletter.png') }}" alt="Icon" class="w-6 h-6" />
                        <div>
                            <p class="text-sm text-gray-700">Application Letter</p>
                            <div id="modal_view_application_letter"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex flex-col gap-6">
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Interview Date</h3>
                        <p id="modal-interviewDate" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Interview Time</h3>
                        <p id="modal-interviewTime" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-1 mb-2">Google Meet Link</h3>
                        <p id="modal-interviewLink" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createInterviewDetailsModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeCreateInterviewDetailsModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <form id="interviewForm" action="" method="POST" class="space-y-4">
                @csrf
                <h2 class="text-xl font-bold mb-4">Schedule Interview</h2>
                <div>
                    <label for="interviewDate" class="block text-sm font-medium text-gray-700">Interview Date</label>
                    <input type="date" name="interviewDate" class="mt-1 block w-full border border-gray-300 rounded-md" required min="{{date('Y-m-d')}}" required>
                </div>
                <div>
                    <label for="interviewTime" class="block text-sm font-medium text-gray-700">Interview Time</label>
                    <input type="time" name="interviewTime" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="create_modal_meet_link" class="block text-sm font-medium text-gray-700">Google Meet Link</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="text" id="create_modal_meet_link" class="block w-full bg-gray-100 border border-gray-300 rounded-md" readonly placeholder="Click Generate Link ->">
                        <button type="button" id="generate-meet-link-btn" data-url="{{ route('job-provider.meet.create') }}" class="bg-blue-500 text-white px-3 py-1.5 rounded text-sm whitespace-nowrap">Generate</button>
                    </div>
                    <input type="hidden" name="interviewLink" id="interviewLink">
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeCreateInterviewDetailsModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" id="submitInterviewBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:bg-gray-400" disabled>Schedule</button>
                </div>
            </form>
        </div>
    </div>
    <div id="rejectJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Disapprove Job Application?</h3>
                <button onclick="closeRejectJobApplicationModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            <form id="disapproveApplication" method="POST" action="">
                @csrf
                @method('PUT')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-100">Yes</button>
            </form>
            <button onclick="closeRejectJobApplicationModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>

    <div id="DeleteApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Delete Job Application?</h3>
                <button onclick="closeDeleteApplicationModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            <form id="deleteApplication" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-100">Yes</button>
            </form>
            <button onclick="closeDeleteApplicationModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>
    <script src="{{ asset('assets/job-provider/manage-job-applications/js/job_provider_job_applications.js') }}"></script>
    <style>
        .main-content-scroll {
            margin-left: 234px;
            padding-top: 4rem;
            height: 100vh;
            overflow-y: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            padding-bottom: 1.5rem;
        }

        #notification-bar {
            transition: opacity 0.5s ease-in-out;
        }
    </style>

</body>

</html>