<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>EQUIJOB - Job Applicant</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased h-full flex">

  <!-- Sidebar for large screens -->
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
    <div class="border-b border-gray-200 bg-white px-4 py-2">
      <h1 class="text-2xl font-semibold text-gray-800">Applicant Dashboard</h1>
      <p class="text-gray-600">Welcome back, {{$user->firstName}}!</p>
    </div>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 bg-[#FCFDFF]">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

        <!-- Apply Card -->
        <div class="flex items-center border border-black h-[83px] bg-white w-full">
          <div class="flex items-center justify-center w-[70px] sm:w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_137.png') }}" alt="Apply icon"
              class="w-[50px] h-[50px] sm:w-[60px] sm:h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-base sm:text-lg font-semibold">Apply</div>
            <div class="text-xl sm:text-2xl">{{$applicationCount}}</div>
          </div>
        </div>

        <div class="flex items-center border border-black h-[83px] bg-white w-full">
          <div class="flex items-center justify-center w-[70px] sm:w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_136.png') }}" alt="Interview icon"
              class="w-[50px] h-[50px] sm:w-[60px] sm:h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-base sm:text-lg font-semibold">Interview</div>
            <div class="text-xl sm:text-2xl">{{$forInterviewCount}}</div>
          </div>
        </div>

        <div class="flex items-center border border-black h-[83px] bg-white w-full">
          <div class="flex items-center justify-center w-[70px] sm:w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/email_1.png') }}" alt="On Offer icon"
              class="w-[50px] h-[50px] sm:w-[60px] sm:h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-base sm:text-lg font-semibold">On Offer</div>
            <div class="text-xl sm:text-2xl">{{$onOfferCount}}</div>
          </div>
        </div>

      </div>
    </main>

  </div>
</body>

</html>