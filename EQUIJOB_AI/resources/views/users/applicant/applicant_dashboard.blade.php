<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Applicant</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased min-h-screen flex">

  <!-- Sidebar -->
  <div class="w-[234px] bg-white hidden lg:block h-screen fixed top-0 left-0">
    <x-applicant-sidebar />
  </div>

  <!-- Main Content Area -->
  <div class="flex flex-col flex-1 w-full lg:ml-[234px] min-h-screen">

    <!-- Topbar -->
    <div class="w-full border-b border-gray-200 shadow-sm z-10">
      <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
    </div>

    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto px-6 py-10 bg-[#FCFDFF]">
      <!-- Status Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Apply Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Apply</div>
            <div class="text-2xl">0</div>
          </div>
        </div>

        <!-- Interview Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Interview</div>
            <div class="text-2xl">0</div>
          </div>
        </div>

        <!-- On Offer Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/email_1.png') }}" alt="On Offer icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">On Offer</div>
            <div class="text-2xl">0</div>
          </div>
        </div>

      </div>
    </main>
  </div>

</body>

</html>
