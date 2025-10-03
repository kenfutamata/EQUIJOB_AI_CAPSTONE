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
        <select
          class="mb-6 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          onchange="if (this.value) window.location.href=this.value">
          <option value="">Filter by Category</option>
          <option value="{{ route('applicant-job-collections') }}" {{ request('category') ? '' : 'selected' }}>All Categories</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'IT & Software']) }}" {{ request('category') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Healthcare']) }}" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Education']) }}" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Business & Finance']) }}" {{ request('category') == 'Business & Finance' ? 'selected' : '' }}>Business & Finance</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Sales & Marketing']) }}" {{ request('category') == 'Sales & Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Customer Service']) }}" {{ request('category') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Human Resources']) }}" {{ request('category') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Design & Creatives']) }}" {{ request('category') == 'Design & Creatives' ? 'selected' : '' }}>Design & Creatives</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Hospitality & Tourism']) }}" {{ request('category') == 'Hospitality & Tourism' ? 'selected' : '' }}>Hospitality & Tourism</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Construction']) }}" {{ request('category') == 'Construction' ? 'selected' : '' }}>Construction</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Manufacturing']) }}" {{ request('category') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Transport & Logistics']) }}" {{ request('category') == 'Transport & Logistics' ? 'selected' : '' }}>Transport & Logistics</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Government']) }}" {{ request('category') == 'Government' ? 'selected' : '' }}>Government</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Government']) }}" {{ request('category') == 'Science & Research' ? 'selected' : '' }}>Science & Research</option>
          <option value="{{ route('applicant-job-collections', ['category' => 'Other']) }}" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($collections as $collection)
          <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $collection->position }}</h2>
            <p class="text-gray-600 mb-4">{{ Str::limit($collection->description, 100) }}</p>
            <p class="text-gray-500 text-sm mb-4">Category: {{ $collection->category }}</p>

            <p class="text-gray-500 text-sm mb-4">Company Name: {{ $collection->companyName }}</p>
            <button onclick="openJobDetailsModal(this)" data-jobposting='@json($collection)' class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-300">View Details</button>
            <a href="{{route('applicant-match-jobs')}}" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors duration-300">Apply Now</a>

          </div>
          @endforeach
        </div>
      </div>
      @if($collections->hasPages())
      <div class="p-4">
        {!! $collections->appends(['category' => request('category')])->links('pagination::tailwind') !!}
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
<script>
  function openJobDetailsModal(button) {
    const jobposting = JSON.parse(button.getAttribute('data-jobposting'));

    document.getElementById('modal-position').textContent = jobposting.position || 'No Position provided';
    document.getElementById('modal-companyName').textContent = jobposting.companyName || 'No Company provided';
    document.getElementById('modal-disabilityType').textContent = jobposting.disabilityType || 'No Disability Type provided';
    document.getElementById('modal-educationalAttainment').textContent = jobposting.educationalAttainment || 'No Educational Attainment provided';
    document.getElementById('modal-workEnvironment').textContent = jobposting.workEnvironment || 'No Work Environment provided';
    document.getElementById('modal-skills').textContent = jobposting.skills || 'No Skills provided';
    document.getElementById('modal-requirements').textContent = jobposting.requirements || 'No Requirements provided';
    document.getElementById('modal-contactPhone').textContent = jobposting.contactPhone || 'No phone number provided';
    document.getElementById('modal-contactEmail').textContent = jobposting.contactEmail || 'No contact email provided';
    document.getElementById('modal-description').textContent = jobposting.description || 'No description provided';
    document.getElementById('modal-salaryRange').textContent = jobposting.salaryRange || 'No Salary Range provided';
    document.getElementById('modal-category').textContent = jobposting.category || 'No Category provided';

    const companyLogo = document.getElementById('modal-companyLogo');
    if (jobposting.companyLogo) {
      companyLogo.src = `/storage/${jobposting.companyLogo}`;
      companyLogo.style.display = 'block';
    } else {
      companyLogo.style.display = 'none';
    }

    document.getElementById('viewJobDetailsModal').classList.remove('hidden');
  }

  function closeViewJobDetailsModal() {
    document.getElementById('viewJobDetailsModal').classList.add('hidden');
  }
</script>

</html>