<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Job Postings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800">
    <!-- Navbar -->
    <x-landing-page-navbar />

    <!-- Hero -->
    <section class="relative bg-blue-900 text-white">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/70 to-blue-900/50"></div>
        <div class="relative max-w-4xl mx-auto px-6 py-32 text-center space-y-6">
            <h1 class="text-4xl md:text-6xl font-bold">Job Postings</h1>
            <p class="text-lg md:text-xl leading-relaxed">
                Explore a wide range of job opportunities from inclusive employers who value diversity and accessibility.
                Whether you're looking for full-time, part-time, or freelance work, EQUIJOB connects you with positions that match your skills and preferences.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="flex-1 px-4 sm:px-6 py-10 bg-[#FCFDFF]">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Job Collections</h1>
                <select
                    class="p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="if (this.value) window.location.href=this.value">
                    <option value="">Filter by Category</option>
                    <option value="{{ url('/jobs') }}" {{ request('category') ? '' : 'selected' }}>All Categories</option>

                    <option value="{{ url('/jobs') }}?category={{ urlencode('IT & Software') }}" {{ request('category') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Healthcare') }}" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Education') }}" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Business & Finance') }}" {{ request('category') == 'Business & Finance' ? 'selected' : '' }}>Business & Finance</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Sales & Marketing') }}" {{ request('category') == 'Sales & Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Customer Service') }}" {{ request('category') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Human Resources') }}" {{ request('category') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Design & Creatives') }}" {{ request('category') == 'Design & Creatives' ? 'selected' : '' }}>Design & Creatives</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Hospitality & Tourism') }}" {{ request('category') == 'Hospitality & Tourism' ? 'selected' : '' }}>Hospitality & Tourism</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Construction') }}" {{ request('category') == 'Construction' ? 'selected' : '' }}>Construction</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Manufacturing') }}" {{ request('category') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Transport & Logistics') }}" {{ request('category') == 'Transport & Logistics' ? 'selected' : '' }}>Transport & Logistics</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Government') }}" {{ request('category') == 'Government' ? 'selected' : '' }}>Government</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Science & Research') }}" {{ request('category') == 'Science & Research' ? 'selected' : '' }}>Science & Research</option>
                    <option value="{{ url('/jobs') }}?category={{ urlencode('Other') }}" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($collections as $collection)

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition duration-300 flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $collection->position }}</h2>
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">{{ $collection->category }}</span>
                    </div>
                    <p class="text-gray-600 mb-3">{{ Str::limit($collection->description, 100) }}</p>
                    <p class="text-gray-500 text-sm mb-2">Company: <span class="font-medium">{{ $collection->companyName }}</span></p>
                    <div class="mt-auto flex gap-2">
                        <button onclick="openJobDetailsModal(this)"
                            data-jobposting='@json(array_merge($collection->toArray(), ["companyLogoUrl" => $collection->companyLogo ? asset("storage/" . $collection->companyLogo) : asset("assets/photos/default-company.png")]))'
                            class="flex-1 bg-blue-600 text-white text-center px-4 py-2 rounded hover:bg-blue-700 transition">
                            View Details
                        </button>
                        <a href="{{route('sign-in')}}"
                            class="flex-1 bg-green-600 text-white text-center px-4 py-2 rounded hover:bg-green-700 transition">
                            Apply Now
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @if($collections->hasPages())
            <div class="p-4">
                {!! $collections->appends(['category' => request('category')])->links('pagination::tailwind') !!}
            </div>
            @endif
        </div>
    </main>

    <div id="viewJobDetailsModal"
        class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden"
        aria-hidden="true" aria-modal="true" role="dialog">
        <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6 transform transition-all scale-95 opacity-0"
            id="modalContent">
            <button onclick="closeViewJobDetailsModal()"
                class="absolute top-4 right-4 text-gray-600 hover:text-black text-2xl font-bold">&times;</button>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img id="modal-companyLogo" src="" alt="Company Logo"
                        class="w-44 h-auto border rounded hidden" />
                </div>
                <div>
                    <h2 id="modal-companyName" class="text-2xl font-semibold text-gray-800">Company Name</h2>
                    <p id="modal-position" class="text-xl text-gray-700 mt-1">Position</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Job Information</h3>
                    <div class="space-y-3 text-sm">
                        <p><strong>Disability Type:</strong> <span id="modal-disabilityType" class="text-blue-600"></span></p>
                        <p><strong>Salary:</strong> <span id="modal-salaryRange" class="text-blue-600"></span></p>
                        <p><strong>Contact:</strong> <span id="modal-contactPhone" class="text-blue-600"></span></p>
                        <p><strong>Email:</strong> <span id="modal-contactEmail" class="text-blue-600"></span></p>
                        <p><strong>Environment:</strong> <span id="modal-workEnvironment" class="text-blue-600"></span></p>
                        <p><strong>Category:</strong> <span id="modal-category" class="text-blue-600"></span></p>
                    </div>
                </div>

                <div class="flex-1 flex flex-col gap-6">
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-2">Job Description</h3>
                        <p id="modal-description" class="text-sm text-gray-700 leading-relaxed"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-2">Educational Attainment</h3>
                        <p id="modal-educationalAttainment" class="text-sm text-gray-700"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-2">Skills</h3>
                        <p id="modal-skills" class="text-sm text-gray-700"></p>
                    </div>
                    <div class="border p-4">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-2">Requirements</h3>
                        <p id="modal-requirements" class="text-sm text-gray-700"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />
    <script src="{{ asset('assets/landing_page/js/landing_page/jobs.js') }}"></script>
</body>

</html>