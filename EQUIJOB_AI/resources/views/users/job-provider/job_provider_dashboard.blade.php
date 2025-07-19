<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>EQUIJOB - Job Provider</title>
  <link rel="icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased h-full flex">

  <!-- Sidebar for large screens -->
  <aside class="hidden lg:block w-[234px] bg-white h-screen fixed top-0 left-0 z-30">
    <x-job-provider-sidebar />
  </aside>

  <!-- Sidebar overlay for mobile -->
  <div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

  <!-- Mobile Sidebar -->
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
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <!-- Sidebar Blade -->
      <x-job-provider-sidebar />
    </div>
  </aside>

  <!-- Main content wrapper -->
  <div class="flex flex-col flex-1 min-h-screen w-full lg:ml-[234px]">

    <!-- Topbar -->
    <header class="flex items-center justify-between w-full border-b border-gray-200 shadow-sm px-4 h-16 bg-white">
      <!-- Mobile menu toggle -->
      <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex-1 flex justify-end">
        <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 bg-[#FCFDFF]">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Application Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Application</div>
            <div class="text-2xl">{{$jobApplicationCount}}</div>
          </div>
        </div>

        <!-- Interview Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Interview</div>
            <div class="text-2xl">{{$jobApplicantInterviewCount}}</div>
          </div>
        </div>

        <!-- Hired Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/email_1.png') }}" alt="Hired icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Hired</div>
            <div class="text-2xl">0</div>
          </div>
        </div>

        <!-- Job Posting Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/job_posting.png') }}" alt="Job Posting icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Job Posting</div>
            <div class="text-2xl">{{ $jobPostingCount }}</div>
          </div>
        </div>
      </div>
    </main>

  </div>
</body>

</html>