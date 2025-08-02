<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Job Applicant- Manage Job Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>

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
                            <th class="px-2 py-2">Applicant Number</th>
                            <th class="px-2 py-2">Position</th>
                            <th class="px-2 py-2">Company Name</th>
                            <th class="px-2 py-2">Applicant Name</th>
                            <th class="px-2 py-2">Applicant Phone Number</th>
                            <th class="px-2 py-2">Applicant Address</th>
                            <th class="px-2 py-2">Sex</th>
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
                        $modalData = array_merge($posting->toArray(), [
                        'uploadResume' => $application->uploadResume,
                        'uploadApplicationLetter' => $application->uploadApplicationLetter,
                        'remarks' => $application->remarks,
                        'interviewDate' => $application->interviewDate ? $application->interviewDate->format('F j, Y') : null,
                        'interviewTime' => $application->interviewTime ? $application->interviewTime->format('g:i A') : null,
                        'interviewLink' => $application->interviewLink,
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
            </div>
        </main>
    </div>
    <!-- View Job Posting Modal -->
    <div id="viewJobApplicationModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeviewJobApplicationModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">×</button>
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

    <script>
        function openDisapproveJobPostingModal(button) {
            document.getElementById('DisapproveJobPostingModal').classList.remove('hidden');
        }

        function closeDisapproveJobPostingModal() {
            document.getElementById('DisapproveJobPostingModal').classList.add('hidden');
        }

        function openAddJobPostingModal() {
            document.getElementById('addJobPostingModal').classList.remove('hidden');
        }

        function closeAddJobPostingModal() {
            document.getElementById('addJobPostingModal').classList.add('hidden');
        }

        function openDeleteModal(userId) {
            const form = document.getElementById('deleteuser');
            form.action = `/EQUIJOB/Admin/Manage-User-Applicants/Delete/${userId}`;
            document.getElementById('DeleteRoleModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('DeleteRoleModal').classList.add('hidden');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('viewJobApplicationModal');
            if (e.target === modal) {
                closeviewJobApplicationModal();
            }
        });

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 2500);
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);

        function openViewJobApplicationModal(button) {
            const applicationData = JSON.parse(button.getAttribute('data-application'));
            document.getElementById('modal.position').value = applicationData.position ?? 'N/A';
            document.getElementById('modal.company_name').value = applicationData.companyName ?? 'N/A';
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
                applicationContainer.innerText = 'No Application Letter uploaded.';
            }

            document.getElementById('viewJobApplicationModal').classList.remove('hidden');
        }

        function closeviewJobApplicationModal() {
            document.getElementById('viewJobApplicationModal').classList.add('hidden');
        }

        function openWithdrawModal(button) {
            const formActionUrl = button.getAttribute('data-url');
            const form = document.getElementById('withdrawForm');
            form.action = formActionUrl;
            document.getElementById('withdrawJobApplicationModal').classList.remove('hidden');
        }

        function closeWithdrawModal() {
            document.getElementById('withdrawJobApplicationModal').classList.add('hidden');
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
    </style>
</body>

</html>