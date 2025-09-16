<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Job Provider-Job Posting</title>
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
            <x-job-provider-sidebar />
        </div>

        <!-- Topbar -->
        <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
            <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
        </div>


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
            @elseif(session('Delete_Success'))
            <div id="notification-bar"
                class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
                {{ session('Delete_Success') }}
            </div>
            @endif

            <!-- Page Header -->
            <div class="text-3xl font-semibold mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                <div>
                    <span class="text-gray-800">Manage </span>
                    <span class="text-blue-500">Job Postings</span>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button"
                        onclick="openAddJobPostingModal()"
                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Add Job Posting</button>
                    <form method="GET" action="{{ route('job-provider-job-posting') }}"
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
                            <th class="px-2 py-2">Position {!!sortArrow('position')!!}</th>
                            <th class="px-2 py-2">Company Name {!!sortArrow('companyName')!!}</th>
                            <th class="px-2 py-2">Sex {!!sortArrow('sex')!!}</th>
                            <th class="px-2 py-2">Age {!!sortArrow('age')!!}</th>
                            <th class="px-2 py-2">Disability Type {!!sortArrow('disabilityType')!!}</th>
                            <th class="px-2 py-2">Educational Attainment {!!sortArrow('educationalAttainment')!!}</th>
                            <th class="px-2 py-2">Work Environment {!!sortArrow('workEnvironment')!!}</th>
                            <th class="px-2 py-2">Experience {!!sortArrow('experience')!!}</th>
                            <th class="px-2 py-2">Skills {!!sortArrow('skills')!!}</th>
                            <th class="px-2 py-2">Category {!!sortArrow('catrgory')!!}</th>
                            <th class="px-2 py-2">Status {!!sortArrow('status')!!}</th>
                            <th class="px-2 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($postings as $posting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">{{ $posting->position }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->companyName }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->sex }}</td>
                            <td class="px-2 py-2 max-w-[150px] break-words">{{ $posting->age }}</td>
                            <td class="px-2 py-2">{{ $posting->disabilityType }}</td>
                            <td class="px-2 py-2">{{ $posting->educationalAttainment ?:'No Educational Attainment provided'}}</td>
                            <td class="px-2 py-2">{{ $posting->workEnvironment }}</td>
                            <td class="px-2 py-2">{{ $posting->experience ?: 'No Experience provided'}}</td>
                            <td class="px-2 py-2">{{ $posting->skills ?: 'No Skills provided'}}</td>
                            <td class="px-2 py-2">{{ $posting->category }}</td>
                            <td class="px-2 py-2">{{ $posting->status }}</td>
                            <td class="px-2 py-2 space-y-1">
                                <button onclick="openViewJobPostingModal(this)" data-jobposting='@json($posting)'
                                    class="bg-blue-500 text-white px-2 py-1 rounded">View</button>
                                <button onclick="openDeleteModal(this)"
                                    data-action="{{ route('job-provider-job-posting-delete', $posting->id) }}"
                                    class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
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



    <div id="addJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 space-y-6 relative">
            <button onclick="closeAddJobPostingModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Job Posting</h2>
            <form method="POST" action="{{route('job-provider-job-posting-store')}}" enctype="multipart/form-data" class="space-y-4">
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
                    <label class="block text-xs text-gray-500">Position</label>
                    <input name="position" class="w-full border rounded px-2 py-1" required>
                    @error('position')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Company Name</label>
                    <input name="companyName" class="w-full border rounded px-2 py-1" value="{{$user->company_name}}" readonly>
                    @error('companyName')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Sex</label>
                    <select name="sex" class="w-full border rounded px-2 py-1">
                        <option value="Any">Any</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    @error('sex')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Company Logo</label>
                    @if($user->company_logo)
                    <img src="{{ asset('storage/' . $user->company_logo) }}" alt="Company Logo" class="w-16 h-16 object-cover border rounded mb-2" id="companyLogo" name="companyLogo" style="display: block;">
                    <span id="companyLogoFilename">{{ $user->company_logo ? basename($user->company_logo) : 'No file chosen' }}</span>
                    @endif
                    @error('companyLogo')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">age</label>
                    <input type="number" name="age" class="w-full border rounded px-2 py-1" required>
                    @error('age')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Disability Type</label>
                    <select name="disabilityType" class="w-full border rounded px-2 py-1" required>
                        <option value="Physical">Physical</option>
                        <option value="Visual">Visual</option>
                        <option value="Hearing">Hearing</option>
                        <option value="Intellectual">Intellectual</option>
                    </select>
                    @error('disabilityType')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Educational Attainment</label>
                    <textarea name="educationalAttainment" class="w-full border rounded px-2 py-1"></textarea>
                    @error('educationalAttainment')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Work Environment</label>
                    <select name="workEnvironment" class="w-full border rounded px-2 py-1" required>
                        <option value="On-Site">On-Site</option>
                        <option value="Work From Home">Work From Home</option>
                        <option value="Hybrid">Hybrid</option>

                    </select>
                    @error('workEnvironment')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Job Posting Objectives</label>
                    <input name="jobPostingObjectives" class="w-full border rounded px-2 py-1" required>
                    @error('jobPostingObjectives')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Experience</label>
                    <input name="experience" class="w-full border rounded px-2 py-1">
                    @error('experience')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Skills</label>
                    <input name="skills" class="w-full border rounded px-2 py-1">
                    @error('skills')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Requirements</label>
                    <textarea name="requirements" class="w-full border rounded px-2 py-1" required></textarea>
                    @error('requirements')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Category</label>
                    <select name="category" class="w-full border rounded px-2 py-1" required>
                        <option value="IT & Software">IT & Software</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Education">Education</option>
                        <option value="Business & Finance">Business & Finance</option>
                        <option value="Sales & Marketing">Sales & Marketing</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Human Resources">Human Resources</option>
                        <option value="Design & Creatives">Design & Creatives</option>
                        <option value="Hospitality & Tourism">Hospitality & Tourism</option>
                        <option value="Construction">Construction</option>
                        <option value="Manufacturing">Manufacturing</option>
                        <option value="Transport & Logistics">Transport & Logistics</option>
                        <option value="Government">Government</option>
                        <option value="Science & Research">Science & Research</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('category')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Contact Phone</label>
                    <input name="contactPhone" class="w-full border rounded px-2 py-1" value="{{$user->phone_number}}" readonly>
                    @error('contactPhone')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Contact Email</label>
                    <input type="email" name="contactEmail" class="w-full border rounded px-2 py-1" value="{{$user->email}}" readonly>
                    @error('contactEmail')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Job Description</label>
                    <textarea name="description" class="w-full border rounded px-2 py-1" required></textarea>
                    @error('description')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Salary Range</label>
                    <input name="salaryRange" class="w-full border rounded px-2 py-1" required>
                    @error('salaryRange')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAddJobPostingModal()" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Add</button>
                </div>
            </form>
        </div>
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
                <label class="block text-xs text-gray-500">Salary Range</label>
                <input id="modal.salaryRange" class="w-full border rounded px-2 py-1" disabled>
            </div>
            <div>
                <label class="block text-xs text-gray-500">Remarks</label>
                <textarea id="modal.remarks" class="w-full border rounded px-2 py-1" disabled></textarea>
            </div>
        </div>
    </div>

    <div id="DeleteJobPostingModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Delete Job Posting?</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form id="deletejobpositng" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-200">Yes</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">No</button>
        </div>
    </div>
    <script src="{{ asset('assets/job-provider/manage-job-posting/js/job_posting.js') }}"></script>

</body>

</html>