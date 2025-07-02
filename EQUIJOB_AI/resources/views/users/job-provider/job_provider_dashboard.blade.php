<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Job Provider</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>
<body class="bg-[#FCFDFF] min-h-screen flex flex-col md:flex-row">

  <!-- Sidebar -->
  <x-job-provider-sidebar />

  <!-- Main content wrapper -->
  <div class="flex flex-col flex-1 min-h-screen overflow-hidden">
    
    <!-- Topbar -->
    <div class="w-full border-b border-gray-200 shadow-sm z-10">
      <x-topbar :user="$user" :notifications="$user->notifications" :unreadNotifications="$user->unreadNotifications" />
    </div>


    <!-- Main content -->
    <main class="flex-1 overflow-y-auto px-6 py-0 bg-[#FCFDFF] mt-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Application Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Application</div>
            <div class="text-2xl">0</div>
          </div>
        </div>

        <!-- Interview Card -->
        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Interview</div>
            <div class="text-2xl">0</div>
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
            <div class="text-2xl">{{$jobPostingCount}}</div>
          </div>
        </div>
      </div>
    </main>

  </div>
</body>
</html>
