<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>EQUIJOB - Job Collections</title>
  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased h-full flex">

  <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
    <x-applicant-sidebar />
  </aside>

  <div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

  <aside
    x-show="sidebarOpen"
    x-transition:enter="transition transform duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition transform duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 w-[234px] bg-white z-30 lg:hidden shadow-lg flex flex-col overflow-y-auto">
    <div class="flex flex-col h-full bg-[#c7d4f8]">

      <div class="flex justify-end p-4 bg-[#c7d4f8]">
        <button @click="sidebarOpen = false" class="text-gray-800 hover:text-red-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto">
        <x-applicant-sidebar />
      </div>

    </div>
  </aside>

  <div class="flex flex-col flex-1 min-h-screen w-full lg:ml-[234px]">

    <!-- Header -->
    <header class="flex items-center justify-between w-full border-b border-gray-200 shadow-sm px-4 h-16 bg-white">
      <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
    </header>

    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 bg-[#FCFDFF]">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Job Collections</h1>
        <div class="mb-8 p-4 bg-white border border-gray-200 rounded-lg">
          <form method="GET" action="{{ route('applicant-job-collections') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
              <div class="lg:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by position, company..." class="border rounded px-3 py-2 w-full text-sm" />
              </div>

              <!-- Province -->
              <div>
                <label for="province-select" class="sr-only">Province</label>
                <select name="province" id="province-select" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                  <option value="">All Provinces</option>
                  @foreach($provinces as $province)
                  <option value="{{ $province->id }}" {{ request('province') == $province->id ? 'selected' : '' }}>
                    {{ $province->provinceName }}
                  </option>
                  @endforeach
                </select>
              </div>

              <!-- City -->
              <div>
                <label for="city-select" class="sr-only">City</label>
                <select name="city" id="city-select" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm" {{ $cities->isEmpty() ? 'disabled' : '' }}>
                  <option value="">All Cities</option>
                  @if($cities->isNotEmpty())
                  @foreach($cities as $city)
                  <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                    {{ $city->cityName }}
                  </option>
                  @endforeach
                  @endif
                </select>
                <small id="city-helper-text" class="text-gray-500 text-xs italic {{ request('province') ? 'hidden' : '' }}">Select a province first</small>
              </div>
            </div>

            <!-- Secondary Filters & Actions Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
              <div>
                <label for="category" class="sr-only">Category</label>
                <select name="category" id="category" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                  <option value="">All Categories</option>
                  <option value="IT & Software" {{ request('category') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                  <option value="Healthcare" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                  <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                  <option value="Business & Finance" {{ request('category') == 'Business & Finance' ? 'selected' : '' }}>Business & Finance</option>
                  <option value="Sales & Marketing" {{ request('category') == 'Sales & Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
                  <option value="Customer Service" {{ request('category') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                  <option value="Human Resources" {{ request('category') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                  <option value="Design & Creatives" {{ request('category') == 'Design & Creatives' ? 'selected' : '' }}>Design & Creatives</option>
                  <option value="Hospitality & Tourism" {{ request('category') == 'Hospitality & Tourism' ? 'selected' : '' }}>Hospitality & Tourism</option>
                  <option value="Construction" {{ request('category') == 'Construction' ? 'selected' : '' }}>Construction</option>
                  <option value="Manufacturing" {{ request('category') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                  <option value="Transport & Logistics" {{ request('category') == 'Transport & Logistics' ? 'selected' : '' }}>Transport & Logistics</option>
                  <option value="Government" {{ request('category') == 'Government' ? 'selected' : '' }}>Government</option>
                  <option value="Science & Research" {{ request('category') == 'Science & Research' ? 'selected' : '' }}>Science & Research</option>
                  <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>

              <div class="flex items-center gap-2 text-sm">
                <span>From:</span>
                <input type="date" name="fromDate" value="{{ request('fromDate') }}" class="border rounded px-3 py-2 w-full">
                <span>To:</span>
                <input type="date" name="toDate" value="{{ request('toDate') }}" class="border rounded px-3 py-2 w-full">
              </div>

              <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap hover:bg-blue-700 transition">Apply Filters</button>
                <a href="{{ route('applicant-job-collections') }}" class="w-full bg-gray-500 text-white px-4 py-2 rounded text-sm whitespace-nowrap text-center hover:bg-gray-600 transition">Clear</a>
              </div>
            </div>
          </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @forelse($collections as $collection)
          @php
          $jobData = $collection->toArray();
          $logoPath = $collection->companyLogo;

          if ($logoPath) {
          if (Str::startsWith($logoPath, 'https://')) {
          $fullLogoUrl = $logoPath;
          } else {
          $fullLogoUrl = 'https://zlusioxytbqhxohsfvyr.supabase.co/storage/v1/object/public/equijob_storage/' . ltrim($logoPath, '/');
          }
          } else {
          $fullLogoUrl = asset('assets/applicant/applicant-dashboard/profile_pic.png');
          }

          $jobData['companyLogo'] = $fullLogoUrl;
          @endphp

          <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $collection->position }}</h2>
            <p class="text-gray-600 mb-4">{{ Str::limit($collection->description, 100) }}</p>
            <p class="text-gray-500 text-sm mb-4">Category: {{ $collection->category }}</p>
            <p class="text-gray-500 text-sm mb-4">Company Name: {{ $collection->companyName }}</p>
            <p class="text-gray-500 text-sm mb-4">Salary: {{ $collection->salaryRange }}</p>
            <p class="text-gray-500 text-sm mb-4">Number of Applicants Applied: {{ $collection->job_applications_count ?? 0 }}</p>
            <p class="text-gray-500 text-sm mb-4">Number of Applicants For Interview: {{ $collection->interviews_count ?? 0 }}</p>

            <p class="text-gray-500 text-sm mb-4">Date Posted: {{ $collection->updated_at->format('F j, Y') }}</p>
            <p class="text-gray-500 text-sm mb-4">
              Last Day of Posting: {{ $collection->endDate?->format('F j, Y') ?? 'N/A' }}
            </p>
            <button onclick="openJobDetailsModal(this)" data-jobposting='@json($jobData)' class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-300">View Details</button>

            <a href="{{route('applicant-match-jobs')}}" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors duration-300">Apply Now</a>

          </div>
          @empty
          <div class="col-span-full bg-gray-50 border border-gray-200 text-gray-600 p-8 rounded-lg text-center">
            <h3 class="text-xl font-semibold">No Jobs Found</h3>
            <p class="mt-2">Your search and filters did not match any job postings.</p>
            <a href="{{ route('applicant-job-collections') }}" class="mt-4 inline-block text-blue-600 hover:underline">
              Clear filters and try again
            </a>
          </div>
          @endforelse
        </div>
      </div>

      @if($collections->hasPages())
      <div class="p-4">
        {!! $collections->appends(request()->input())->links('pagination::tailwind') !!}
      </div>
      @endif
    </main>
  </div>

  <div id="viewJobDetailsModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
    <div class="relative bg-white rounded-lg w-full max-w-6xl mx-4 overflow-auto max-h-[90vh] p-8 flex flex-col gap-6">

      <button onclick="closeViewJobDetailsModal()" class="absolute top-4 right-4 text-gray-600 hover:text-black text-xl z-10">&times;</button>

      <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-shrink-0">
          <img id="modal-companyLogo" src="https://placehold.co/180x151" alt="Company Logo" class="w-44 h-auto border" style="display:block;" />
          <div id="modal-companyInitial" class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-blue-500 font-bold text-lg" style="display:none;"></div>
        </div>
        <div>
          <h2 id="modal-companyName" class="text-2xl font-semibold text-gray-800">Company Name</h2>
          <p id="modal-position" class="text-xl text-gray-700 mt-1">Position</p>
        </div>
      </div>

      <div class="flex flex-col md:flex-row gap-6">
        <div class="border w-full md:max-w-xs p-4 flex flex-col gap-4">
          <h3 class="text-lg font-semibold border-b pb-1">Job Information</h3>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/disabled.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Disability Type</p>
              <p id="modal-disabilityType" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/salary.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Salary</p>
              <p id="modal-salaryRange" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/phone.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Contact Number</p>
              <p id="modal-contactPhone" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/workplace.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Work Environment</p>
              <p id="modal-workEnvironment" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/email.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Email Address</p>
              <p id="modal-contactEmail" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/category.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Category</p>
              <p id="modal-category" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <img src="{{ asset('assets/photos/job-applicant/job-recommendations/email.png') }}" alt="Icon" class="w-6 h-6" />
            <div>
              <p class="text-sm text-gray-700">Company Address</p>
              <p id="modal-companyAddress" class="text-sm text-blue-600 font-medium"></p>
            </div>
          </div>
        </div>

        <div class="flex-1 flex flex-col gap-6">
          <div class="border p-4">
            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Job Description</h3>
            <p id="modal-description" class="text-sm text-gray-700 leading-relaxed"></p>
          </div>
          <div class="border p-4">
            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Educational Attainment</h3>
            <p id="modal-educationalAttainment" class="text-sm text-gray-700 leading-relaxed"></p>
          </div>
          <div class="border p-4">
            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Skills</h3>
            <p id="modal-skills" class="text-sm text-gray-700 leading-relaxed"></p>
          </div>
          <div class="border p-4">
            <h3 class="text-lg font-semibold border-b pb-1 mb-2">Requirements</h3>
            <p id="modal-requirements" class="text-sm text-gray-700 leading-relaxed"></p>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>
<script src="{{ asset('assets/applicant/applicant-job-collections/js/applicant_job_collections.js') }}"></script>

</html>