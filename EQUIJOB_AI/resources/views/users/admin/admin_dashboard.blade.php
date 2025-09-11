<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Admin</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

<body class="bg-[#FCFDFF] min-h-screen flex flex-col md:flex-row">

  <div class="w-[234px] bg-white hidden lg:block h-screen fixed top-0 left-0">
    <x-admin-sidebar />
  </div>

  <div class="flex flex-col flex-1 w-full lg:ml-[234px] min-h-screen">

    <div class="w-full border-b border-gray-200 shadow-sm z-10">
      <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications"/>
    </div>

    <main class="flex-1 overflow-y-auto px-6 py-10 bg-[#FCFDFF]">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/admin/admin-dashboard/image_137.png') }}" alt="Applicants icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Applicants</div>
            <div class="text-2xl">{{ $applicantCount }}</div>
          </div>
        </div>

        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/admin/admin-dashboard/image_136.png') }}" alt="Registered icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Registered</div>
            <div class="text-2xl">{{ $userCount }}</div>
          </div>
        </div>

        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/admin/admin-dashboard/email_1.png') }}" alt="Job Providers icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Job Providers</div>
            <div class="text-2xl">{{ $jobProviderCount }}</div>
          </div>
        </div>

        <div class="flex border border-black w-full h-[83px] bg-white">
          <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
            <img src="{{ asset('assets/admin/admin-dashboard/hired.png') }}" alt="Hired icon" class="w-[60px] h-[60px]" />
          </div>
          <div class="flex flex-col justify-center px-4">
            <div class="text-lg font-semibold">Hired</div>
            <div class="text-2xl">{{$hiredCount}}</div>
          </div>
        </div>

      </div>
    </main>
  </div>

</body>
</html>
