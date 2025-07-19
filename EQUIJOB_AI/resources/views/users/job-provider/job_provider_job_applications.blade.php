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

            <!-- Job Postings Table -->
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
                        $jobpostingData = array_merge($posting->toArray(), [
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'remarks' => $application->remarks,
                        ]);
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
                                    data-jobposting='@json($jobpostingData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button
                                    onclick="openCreateInterviewDetailsModal({{ $application->id }})"
                                    class="bg-green-500 text-white px-2 py-1 rounded">
                                    For Interview
                                </button>
                                <button
                                    onclick="openDisapproveJobPostingModal(this)"
                                    data-jobposting='@json($posting)'
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Disapprove
                                </button>
                                @elseif($application->status == 'For Interview')
                                <button
                                    onclick="openViewJobApplicationsModal(this)"
                                    data-jobposting='@json($jobpostingData)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
                                </button>
                                <button
                                    onclick="openCreateInterviewDetailsModal({{ $application->id }})"
                                    class="bg-green-500 text-white px-2 py-1 rounded">
                                    Hire
                                </button>
                                <button
                                    onclick="openDisapproveJobPostingModal(this)"
                                    data-jobposting='@json($posting)'
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Disapprove
                                </button>
                                @elseif($application->status == 'Rejected')
                                <button
                                    onclick="openViewJobPostingModal(this)"
                                    data-jobposting='@json($posting)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                    View
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

    <!-- View Job Posting Modal -->
    <div id="viewJobApplicationsModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeViewJobApplicationsModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">View Job Posting</h2>
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
                <input id="modal.disability_type" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Time</label>
                <input id="modal.disability_type" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Google Meet Link</label>
                <input type="text" id="modal_meet_link" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm" readonly placeholder="Generating link...">
                <input type="hidden" name="interview_link" id="interview_link_hidden">
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
                @method('POST')
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

    <!-- Scripts -->
    <script>
        async function openCreateInterviewDetailsModal(applicationId) {
            const modal = document.getElementById('createInterviewDetailsModal');
            const form = document.getElementById('interviewForm');
            const meetLinkInput = document.getElementById('create_modal_meet_link');
            const hiddenLinkInput = document.getElementById('interviewLink');
            const submitBtn = document.getElementById('submitInterviewBtn');
            form.action = '/EQUIJOB/Job-Provider/Manage-Job-Applications/${applicationId}/schedule-interview';
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


        function closeCreateInterviewDetailsModal(button) {
            document.getElementById('createInterviewDetailsModal').classList.add('hidden');

        }

        function openDisapproveJobApplicationModal(button) {
            document.getElementById('DisapproveJobPostingModal').classList.remove('hidden');
        }

        function closeDisapproveJobApplicationModal() {
            document.getElementById('DisapproveJobPostingModal').classList.add('hidden');
        }

        function openViewJobApplicationsModal(button) {
            const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
            document.getElementById('modal.position').value = jobposting.position ?? '';
            document.getElementById('modal.company_name').value = jobposting.companyName ?? '';
            document.getElementById('modal.sex').value = jobposting.sex ?? '';
            document.getElementById('modal.age').value = jobposting.age ?? '';
            document.getElementById('modal.disability_type').value = jobposting.disabilityType ?? '';
            document.getElementById('modal.remarks').value = jobposting.remarks ?? '';

            const resumeContainer = document.getElementById('modal_view_resume');
            resumeContainer.innerHTML = '';
            if (jobposting.uploadResume) {
                const ext = jobposting.uploadResume.split('.').pop().toLowerCase();
                const filePath = `/storage/${jobposting.uploadResume}`;
                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    resumeContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
                } else if (ext === 'pdf') {
                    resumeContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Resume (PDF)</a>`;
                } else {
                    resumeContainer.innerText = 'Unsupported file format';
                }
            }

            const applicationContainer = document.getElementById('modal_view_application_letter');
            applicationContainer.innerHTML = '';
            if (jobposting.uploadApplicationLetter) {
                const ext = jobposting.uploadApplicationLetter.split('.').pop().toLowerCase();
                const filePath = `/storage/${jobposting.uploadApplicationLetter}`;
                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    applicationContainer.innerHTML = `<img src="${filePath}" class="w-[100px] h-[100px] object-cover" />`;
                } else if (ext === 'pdf') {
                    applicationContainer.innerHTML = `<a href="${filePath}" target="_blank" class="text-blue-500 underline">View Application Letter (PDF)</a>`;
                } else {
                    applicationContainer.innerText = 'Unsupported file format';
                }
            }

            document.getElementById('viewJobApplicationsModal').classList.remove('hidden');
        }

        function closeViewJobApplicationsModal() {
            document.getElementById('viewJobApplicationsModal').classList.add('hidden');
        }

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 2500);
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);
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
    </style>
</body>

</html>