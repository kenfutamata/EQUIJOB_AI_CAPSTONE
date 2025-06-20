<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Admin-Job Posting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>

<body class="bg-white text-black">

    <div>
        <!-- Sidebar -->
        <div class="fixed top-0 left-0 w-[234px] h-full z-40" style="background-color: #c3d2f7;">
            <x-admin-sidebar />
        </div>

        <!-- Topbar -->
        <header class="fixed top-0 left-[234px] right-0 h-[64px] z-30 bg-white border-b border-gray-200 shadow-none">
            <x-topbar :user="$user" />
        </header>

        <!-- Main Content -->
        <main class="ml-[234px] mt-[64px] h-[calc(100vh-64px)] overflow-y-auto p-6 bg-gray-50">

            @if(session('Success'))
            <div id="notification-bar"
                class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Success') }}
            </div>
            @elseif(session('error'))
            <div id="notification-bar"
                class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('error') }}
            </div>
            @endif

            <!-- Page Header -->
            <div class="text-3xl font-semibold mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                <div>
                    <span class="text-gray-800">Manage </span>
                    <span class="text-blue-500">Job Postings</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="{{ route('admin-manage-user-applicants') }}"
                        class="flex items-center gap-1 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Job Posting"
                            class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit"
                            class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
            </div>

            <!-- Job Postings Table -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-2 py-2">Id</th>
                            <th class="px-2 py-2">Job Provider ID</th>
                            <th class="px-2 py-2">Position</th>
                            <th class="px-2 py-2">Company Name</th>
                            <th class="px-2 py-2">Sex</th>
                            <th class="px-2 py-2">Company Logo</th>
                            <th class="px-2 py-2">Age</th>
                            <th class="px-2 py-2">Disability Type</th>
                            <th class="px-2 py-2">Educational Attainment</th>
                            <th class="px-2 py-2">Job Posting Objectives</th>
                            <th class="px-2 py-2">Experience</th>
                            <th class="px-2 py-2">Skills</th>
                            <th class="px-2 py-2">Requirements</th>
                            <th class="px-2 py-2">Contact Phone</th>
                            <th class="px-2 py-2">Contact Email</th>
                            <th class="px-2 py-2">Job Description</th>
                            <th class="px-2 py-2">Salary Range</th>
                            <th class="px-2 py-2">Status</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($postings as $posting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">{{ $posting->id }}</td>
                            <td class="px-2 py-2">{{ $posting->job_provider_id }}</td>
                            <td class="px-2 py-2">{{ $posting->position }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->company_name }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->sex }}</td>
                            <td class="px-2 py-2">
                                @if ($posting->company_logo)
                                <img src="{{ asset('storage/' . $posting->company_logo) }}"
                                    class="w-8 h-8 object-cover mx-auto">
                                @else
                                No Logo
                                @endif
                            </td>
                            <td class="px-2 py-2">{{ $posting->age }}</td>
                            <td class="px-2 py-2">{{ $posting->disability_type }}</td>
                            <td class="px-2 py-2">{{ $posting->educational_attainment }}</td>
                            <td class="px-2 py-2">{{ $posting->job_posting_objectives }}</td>
                            <td class="px-2 py-2">{{ $posting->experience }}</td>
                            <td class="px-2 py-2">{{ $posting->skills }}</td>
                            <td class="px-2 py-2">{{ $posting->requirements }}</td>
                            <td class="px-2 py-2">{{ $posting->contact_phone }}</td>
                            <td class="px-2 py-2">{{ $posting->contact_email }}</td>
                            <td class="px-2 py-2">{{ $posting->description }}</td>
                            <td class="px-2 py-2">{{ $posting->salary_range }}</td>
                            <td class="px-2 py-2">{{ $posting->status }}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if ($posting->status === 'Pending')
                                <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <form action="{{route('admin-manage-job-posting-for-posting', $posting->id)}}" method="post" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">For Posting</button>
                                </form>
                                <form action="{{route('admin-manage-job-posting-disapproved', $posting->id)}}" method="post" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Disapprove</button>
                                </form>
                                @elseif ($posting->status === 'For Posting')
                                    <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @elseif ($posting->status === 'Disapproved')
                                    <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="viewJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeViewJobPostingModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
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
                <label class="block text-xs text-gray-500">Company Logo</label>
                <img id="modal.company_logo" class="w-16 h-16 object-cover border rounded" style="display:none;">
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
                <label class="block text-xs text-gray-500">Educational Attainment</label>
                <input id="modal.educational_attainment" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Job Posting Objectives</label>
                <input id="modal.job_posting_objectives" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Experience</label>
                <input id="modal.experience" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Skills</label>
                <input id="modal.skills" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Requirements</label>
                <textarea id="modal.requirements" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Contact Phone</label>
                <input id="modal.contact_phone" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Contact Email</label>
                <input id="modal.contact_email" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Job Description</label>
                <textarea id="modal.description" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Salary Range</label>
                <input id="modal.salary_range" class="w-full border rounded px-2 py-1" disabled>
            </div>

        </div>
    </div>
    <!-- Scripts -->
    <script>
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
            const modal = document.getElementById('viewProfileModal');
            if (e.target === modal) closeModal();
        });

        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.opacity = '0';
        }, 2500);
        setTimeout(() => {
            const notif = document.getElementById('notification-bar');
            if (notif) notif.style.display = 'none';
        }, 3000);

        function openViewJobPostingModal(button) {
            const jobposting = JSON.parse(button.getAttribute('data-jobposting'));
            document.getElementById('modal.position').value = jobposting.position;
            document.getElementById('modal.company_name').value = jobposting.company_name;
            document.getElementById('modal.sex').value = jobposting.sex;
            const companyLogo = document.getElementById('modal.company_logo');
            if (jobposting.company_logo) {
                companyLogo.src = `/storage/${jobposting.company_logo}`;
                companyLogo.style.display = 'block';
            } else {
                companyLogo.style.display = 'none';
            }
            document.getElementById('modal.age').value = jobposting.age;
            document.getElementById('modal.disability_type').value = jobposting.disability_type;
            document.getElementById('modal.educational_attainment').value = jobposting.educational_attainment;
            document.getElementById('modal.job_posting_objectives').value = jobposting.job_posting_objectives;
            document.getElementById('modal.experience').value = jobposting.experience;
            document.getElementById('modal.skills').value = jobposting.skills;
            document.getElementById('modal.requirements').value = jobposting.requirements;
            document.getElementById('modal.contact_phone').value = jobposting.contact_phone;
            document.getElementById('modal.contact_email').value = jobposting.contact_email;
            document.getElementById('modal.description').value = jobposting.description;
            document.getElementById('modal.salary_range').value = jobposting.salary_range;
            document.getElementById('viewJobPostingModal').classList.remove('hidden');

        }

        function closeViewJobPostingModal() {
            document.getElementById('viewJobPostingModal').classList.add('hidden');
        }
    </script>

</body>

</html>