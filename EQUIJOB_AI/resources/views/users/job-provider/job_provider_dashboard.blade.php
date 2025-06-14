<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Job Provider</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>
<body class="bg-[#FCFDFF] flex flex-col md:flex-row min-h-screen">

  <!-- Sidebar -->
  <x-job-provider-sidebar />

  <!-- Main content -->
     <x-topbar :user="$user"/>
    <!-- Status Cards -->
<div class="flex flex-col md:flex-row gap-6 px-6 py-10">
  
  <!-- Apply Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Application</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

  <!-- Interview Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Interview</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

  <!-- On Offer Card -->
  <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/email_1.png') }}" alt="On Offer icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Hired</div>
      <div class="text-2xl">0</div>
    </div>
  </div>
<!--Job Postings-->
    <div class="flex border border-black w-full max-w-[230px] h-[83px]">
    <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
      <img src="{{ asset('assets/job-provider/job-provider-dashboard/job_posting.png') }}" alt="On Offer icon" class="w-[60px] h-[60px]" />
    </div>
    <div class="flex flex-col justify-center px-4">
      <div class="text-lg font-semibold">Job Posting</div>
      <div class="text-2xl">0</div>
    </div>
  </div>

</div>
</body>
</html>
