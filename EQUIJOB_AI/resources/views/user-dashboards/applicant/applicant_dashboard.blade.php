<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Applicant</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>
<body class="bg-[#FCFDFF] flex flex-col md:flex-row min-h-screen">

  <!-- Sidebar -->
  <x-applicant-sidebar />

  <!-- Main content -->
  <div class="flex flex-col flex-1">
    <!-- Top bar -->
    <div class="flex justify-end items-center h-16 px-4 border-b border-gray-300 bg-white">
      <!-- Notification Icon -->
      <div class="mr-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </div>

      <!-- Profile box -->
      <a href="/applicant/profile" class="flex items-center border border-black px-2 py-1 bg-white hover:bg-gray-100 transition rounded w-[170px] h-[50px]">
        <img src="{{ asset('assets/applicant/dashboard/profile_pic.png') }}" alt="User avatar" class="rounded-full w-10 h-11 mr-2" />
        <div class="text-xs font-medium">
          <div class ="text-[8px]">{{$user->email ?? 'No Email Found'}}</div>
          <div class="text-[10px] text-gray-600">Pwd no. {{$user->pwd_card ?? 'N/A'}}</div>
        </div>
      </a>
    </div>

    <!-- Status Cards -->
<div class="flex flex-col md:flex-row gap-6 px-6 py-10">
  
  <!-- Apply Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/applicant/dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Apply</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

  <!-- Interview Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/applicant/dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Interview</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

  <!-- On Offer Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/applicant/dashboard/email_1.png') }}" alt="On Offer icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">On Offer</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

</div>
</body>
</html>
