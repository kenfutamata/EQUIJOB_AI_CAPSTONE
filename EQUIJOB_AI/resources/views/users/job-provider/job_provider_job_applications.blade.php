<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>EQUIJOB - Job Provider- Manage Job Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>

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
            @elseif ($errors->any())
            <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 max-w-md w-full">
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
                            <th class="px-2 py-2">Applicant Number</th>
                            <th class="px-2 py-2">Position</th>
                            <th class="px-2 py-2">Company Name</th>
                            <th class="px-2 py-2">Applicant Name</th>
                            <th class="px-2 py-2">Applicant Phone Number</th>
                            <th class="px-2 py-2">Sex</th>
                            <th class="px-2 py-2">Applicant Address</th>
                            <th class="px-2 py-2">Email Address</th>
                            <th class="px-2 py-2">Applicant Disability Type</th>
                            <th class="px-2 py-2">Status</th>
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
                        'sex' => $applicant->gender ?? 'N/A',
                        'age' => $applicant->age ?? 'N/A',
                        'disabilityType' => $applicant->type_of_disability ?? 'N/A',
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'remarks' => $application->remarks,
                        'interviewDate' => $application->interviewDate ? \Carbon\Carbon::parse($application->interviewDate)->format('F j, Y') : null,
                        'interviewTime' => $application->interviewTime ? \Carbon\Carbon::parse($application->interviewTime)->format('g:i A') : null,
                        'interviewLink' => $application->interviewLink
                        ];
                        @endphp
                        <tr>
                            <td class="px-2 py-2">{{ $application->jobApplicationNumber ?? $application->id }}</td>
                            <td class="px-2 py-2">{{ $posting->position ?? '' }}</td>
                            <td class="px-2 py-2">{{ $posting->companyName ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->first_name ?? '' }} {{ $applicant->last_name ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->phone_number ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->gender ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->address ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->email ?? '' }}</td>
                            <td class="px-2 py-2">{{ $applicant->type_of_disability ?? '' }}</td>
                            <td class="px-2 py-2">{{ $application->status ?? '' }}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if ($application->status === 'Pending')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button
                                    onclick="openCreateInterviewDetailsModal({{ $application->id }})"
                                    class="bg-green-500 text-white px-2 py-1 rounded">
                                    For Interview
                                </button>
                                <button onclick="openRejectJobApplicationModal(this)" data-url="{{route('job-provider-manage-job-applications.reject', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded">Disapprove </button>
                                <button
                                    onclick="openDeleteApplicationModal({{$application->id}})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Delete
                                </button>
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
                                <button onclick="openRejectJobApplicationModal(this)" data-url="{{route('job-provider-manage-job-applications.reject', ['id'=> $application->id])}}" class="bg-red-500 text-white px-2 py-1 rounded">Disapprove </button>
                                <button
                                    onclick="openDeleteApplicationModal({{$application->id}})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Delete
                                </button>
                                @elseif($application->status == 'On-Offer')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button
                                    onclick="openDeleteApplicationModal({{$application->id}})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Delete
                                </button>
                                @else
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-application='@json($modalData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button
                                    onclick="openDeleteApplicationModal({{$application->id}})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Delete
                                </button>
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

    {{-- Modals remain unchanged --}}
    <div id="viewJobApplicationsModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeViewJobApplicationsModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">×</button>
            <h2 class="text-xl font-bold mb-4">View Job Application</h2>
            <div>
                <label class="block text-xs text-gray-500">Position</label>
                <input id="modal.position" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Company Name</label>
                <input id="modal.company_name" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Sex</label>
                <input id="modal.sex" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Age</label>
                <input id="modal.age" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Disability Type</label>
                <input id="modal.disability_type" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Resume</label>
                <div id="modal_view_resume"></div>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Application Letter</label>
                <div id="modal_view_application_letter"></div>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Interview Date</label>
                <input id="modal.interviewDate" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Time</label>
                <input id="modal.interviewTime" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Google Meet Link</label>
                <input type="text" id="modal_meet_link" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm" readonly>
                <input type="hidden" name="modal.interviewLink" id="modal.interviewLink">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mt-2">Remarks</label>
                <textarea id="modal.remarks" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
        </div>
    </div>

    <div id="createInterviewDetailsModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeCreateInterviewDetailsModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">×</button>
            <form id="interviewForm" action="" method="POST" class="space-y-4">
                @csrf
                <h2 class="text-xl font-bold mb-4">Schedule Interview</h2>
                <div>
                    <label for="interviewDate" class="block text-sm font-medium text-gray-700">Interview Date</label>
                    <input type="date" id="interviewDate" name="interviewDate" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="interviewTime" class="block text-sm font-medium text-gray-700">Interview Time</label>
                    <input type="time" id="interviewTime" name="interviewTime" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="create_modal_meet_link" class="block text-sm font-medium text-gray-700">Google Meet Link</label>
                    <input type="text" id="create_modal_meet_link" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md" readonly placeholder="Generating link...">
                    <input type="hidden" name="interviewLink" id="interviewLink">
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeCreateInterviewDetailsModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" id="submitInterviewBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 disabled:bg-gray-400" disabled>
                        Schedule Interview
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="rejectJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Disapprove Application</h3>
                <button onclick="closeRejectJobApplicationModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="remarks-input" class="block text-sm font-medium text-gray-700 mb-1">Please state your reason for Disapproving the application.</label>
                    <textarea id="remarks-input" name="remarks" rows="4" class="w-full border rounded px-2 py-1" required></textarea>
                </div>
                <button type="submit" class="w-full py-2 px-4 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">Submit Disapproval</button>
            </form>
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
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-gray-50">Yes</button>
            </form>
            <button onclick="closeDeleteApplicationModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Cancel</button>
        </div>
    </div>
    
    <script>
        async function openCreateInterviewDetailsModal(applicationId) {
            const modal = document.getElementById('createInterviewDetailsModal');
            const form = document.getElementById('interviewForm');
            const meetLinkInput = document.getElementById('create_modal_meet_link');
            const hiddenLinkInput = document.getElementById('interviewLink');
            const submitBtn = document.getElementById('submitInterviewBtn');
            form.action = `/EQUIJOB/Job-Provider/Manage-Job-Applications/${applicationId}/schedule-interview`;
            meetLinkInput.value = 'Generating link, please wait...';
            hiddenLinkInput.value = '';
            submitBtn.disabled = true;
            modal.classList.remove('hidden');

            try {
                const response = await fetch("{{ route('job-provider-manage-job-applications.google.meet.create_link') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                if (response.ok) {
                    meetLinkInput.value = data.meetLink;
                    hiddenLinkInput.value = data.meetLink;
                    submitBtn.disabled = false;
                } else {
                    meetLinkInput.value = `Error: ${data.error || 'Could not generate link.'}`;
                }
            } catch (error) {
                meetLinkInput.value = 'A network error occurred. Please try again.';
                console.error('Fetch error:', error);
            }
        }

        function closeCreateInterviewDetailsModal() {
            document.getElementById('createInterviewDetailsModal').classList.add('hidden');
        }

        function openViewJobApplicationsModal(button) {
            const applicationData = JSON.parse(button.getAttribute('data-application'));
            document.getElementById('modal.position').value = applicationData.position ?? 'N/A';
            document.getElementById('modal.company_name').value = applicationData.companyName ?? 'N/A';
            document.getElementById('modal.sex').value = applicationData.sex ?? 'N/A';
            document.getElementById('modal.age').value = applicationData.age ?? 'N/A';
            document.getElementById('modal.disability_type').value = applicationData.disabilityType ?? 'N/A';
            document.getElementById('modal.remarks').value = applicationData.remarks ?? 'N/A';
            document.getElementById('modal.interviewDate').value = applicationData.interviewDate ?? 'N/A';
            document.getElementById('modal.interviewTime').value = applicationData.interviewTime ?? 'N/A';
            document.getElementById('modal_meet_link').value = applicationData.interviewLink ?? 'N/A';
            document.getElementById('modal.interviewLink').value = applicationData.interviewLink ?? '';

            const resumeContainer = document.getElementById('modal_view_resume');
            resumeContainer.innerHTML = '';
            if (applicationData.uploadResume) {
                const ext = applicationData.uploadResume.split('.').pop().toLowerCase();
                const filePath = `/storage/${applicationData.uploadResume}`;
                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    resumeContainer.innerHTML = `<a href="${filePath}" target="_blank"><img src="${filePath}" class="w-[100px] h-[100px] object-cover" alt="Resume Preview"/></a>`;
                } else if (ext === 'pdf') {
                    resumeContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Resume (PDF)</a>`;
                } else {
                    resumeContainer.innerText = 'Unsupported file format';
                }
            } else {
                resumeContainer.innerText = 'No resume uploaded.';
            }

            const applicationContainer = document.getElementById('modal_view_application_letter');
            applicationContainer.innerHTML = '';
            if (applicationData.uploadApplicationLetter) {
                const ext = applicationData.uploadApplicationLetter.split('.').pop().toLowerCase();
                const filePath = `/storage/${applicationData.uploadApplicationLetter}`;
                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    applicationContainer.innerHTML = `<a href="${filePath}" target="_blank"><img src="${filePath}" class="w-[100px] h-[100px] object-cover" alt="Application Letter Preview"/></a>`;
                } else if (ext === 'pdf') {
                    applicationContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Application Letter (PDF)</a>`;
                } else {
                    applicationContainer.innerText = 'Unsupported file format';
                }
            } else {
                applicationContainer.innerText = 'No letter uploaded.';
            }
            document.getElementById('viewJobApplicationsModal').classList.remove('hidden');
        }

        function closeViewJobApplicationsModal() {
            document.getElementById('viewJobApplicationsModal').classList.add('hidden');
        }

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 3500); // Increased time slightly for readability of lists

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 4000);

        function openRejectJobApplicationModal(button) {
            const formActionUrl = button.getAttribute('data-url');
            const form = document.getElementById('rejectForm');
            form.action = formActionUrl;
            document.getElementById('rejectJobApplicationModal').classList.remove('hidden');
        }

        function closeRejectJobApplicationModal() {
            document.getElementById('rejectJobApplicationModal').classList.add('hidden');
        }

        function openDeleteApplicationModal(applicationId) {
            const form = document.getElementById('deleteApplication');
            form.action = `/EQUIJOB/Job-Provider/Manage-Job-Applications/Delete/${applicationId}`;
            document.getElementById('DeleteApplicationModal').classList.remove('hidden');
        }

        function closeDeleteApplicationModal() {
            document.getElementById('DeleteApplicationModal').classList.add('hidden');
        }
    </script>

    <!-- Style -->
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