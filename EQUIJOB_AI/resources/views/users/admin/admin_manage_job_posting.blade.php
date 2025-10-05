<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Admin-Job Posting</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

<body class="bg-white text-black">
    <div>
        <!-- Sidebar -->
        <div class="fixed top-0 left-0 w-[234px] h-full z-40" style="background-color: #c3d2f7;">
            <x-admin-sidebar />
        </div>

        <!-- Topbar -->
        <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
            <x-topbar :user="$admin" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
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
                    <span class="text-blue-500">Job Postings</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin-manage-job-posting-export') }}" class="bg-green-500 text-white px-2 py-1 rounded text-base">Export to Excel</a>

                    <form method="GET" action="{{ route('admin-manage-job-posting') }}" class="flex items-center gap-1 ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Job Posting" class="border rounded-l px-2 py-1 w-32 text-sm" />
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded-r text-sm">Search</button>
                    </form>
                </div>
            </div>

            <!-- Job Postings Table -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 font-semibold">
                        <tr>
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Position {!! sortArrow('position')!!}</th>
                            <th class="px-2 py-2">Company Name {!! sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Disability Type {!! sortArrow('disabilityType')!!}</th>
                            <th class="px-2 py-2">Educational Attainment {!! sortArrow('educationalAttainment')!!}</th>
                            <th class="px-2 py-2">Work Environment {!! sortArrow('workEnvironment')!!}</th>
                            <th class="px-2 py-2">Experience {!! sortArrow('experience')!!}</th>
                            <th class="px-2 py-2">Skills {!! sortArrow('skills')!!}</th>
                            <th class="px-2 py-2">Category {!! sortArrow('category')!!}</th>
                            <th class="px-2 py-2">Status {!! sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($postings as $posting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">{{ $postings->firstItem() + $loop->index}}</td>
                            <td class="px-2 py-2">{{ $posting->position }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->companyName }}</td>
                            <td class="px-2 py-2">{{ $posting->disabilityType }}</td>
                            <td class="px-2 py-2">{{ $posting->educationalAttainment }}</td>
                            <td class="px-2 py-2">{{ $posting->workEnvironment }}</td>
                            <td class="px-2 py-2">{{ $posting->experience }}</td>
                            <td class="px-2 py-2">{{ $posting->skills }}</td>
                            <td class="px-2 py-2">{{ $posting->category }}</td>
                            <td class="px-2 py-2">{{ $posting->status }}</td>
                            <td class="px-2 py-2 space-y-1">
                                @if ($posting->status === 'Pending')
                                <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <form action="{{route('admin-manage-job-posting-for-posting', $posting->id)}}" method="post" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">For Posting</button>
                                </form>
                                <button onclick="openDisapproveJobPostingModal(this)" data-jobposting='@json($posting)' class="bg-red-500 text-white px-2 py-1 rounded">Disapprove</button>
                                @else
                                <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)' class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-center">
                    {!! $postings->links('pagination::tailwind') !!}
                </div>
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
                <input id="modal.companyName" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Sex</label>
                <input id="modal.sex" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Company Logo</label>
                <img id="modal.companyLogo" class="w-16 h-16 object-cover border rounded" style="display:none;">
            </div>
            <div>
                <label class="block text-xs text-gray-500">Age</label>
                <input id="modal.age" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Disability Type</label>
                <input id="modal.disabilityType" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Educational Attainment</label>
                <input id="modal.educationalAttainment" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Work Environment</label>
                <input id="modal.workEnvironment" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Job Posting Objectives</label>
                <input id="modal.jobPostingObjectives" class="w-full border rounded px-2 py-1" disabled>
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
                <input id="modal.contactPhone" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Contact Email</label>
                <input id="modal.contactEmail" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Job Description</label>
                <textarea id="modal.description" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Category</label>
                <input id="modal.category" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Salary Range</label>
                <input id="modal.salaryRange" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Remarks</label>
                <textarea id="modal.remarks" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
        </div>
    </div>

    <div id="DisapproveJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Please Input your Remarks for Disapproval of Job Posting</h3>
                <button onclick="closeDisapproveJobPostingModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="disapproveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="4" class="w-full border rounded px-2 py-1" required></textarea>
                    <button type="submit" class="w-full py-3 px-4 rounded-lg bg-gray-50">Submit</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/admin/admin_manage_job_posting/js/admin_manage_job_posting.js') }}"></script>
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