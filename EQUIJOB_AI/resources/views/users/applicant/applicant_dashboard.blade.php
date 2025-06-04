<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>EQUIJOB - Applicant</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
</head>

<body class="bg-[#FCFDFF] text-gray-800 font-sans antialiased min-h-screen">
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <div class="w-[234px] h-full bg-white hidden lg:block">
      <x-applicant-sidebar />
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1">

      <!-- Topbar -->
      <x-topbar :user="$user" class="flex-shrink-0" />

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-6 md:p-10">
        <!-- Status Cards -->
        <div class="flex flex-col md:flex-row gap-6">

          <!-- Apply Card -->
          <div class="flex border border-black w-full max-w-[230px] h-[83px]">
            <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
              <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_137.png') }}" alt="Apply icon" class="w-[60px] h-[60px]" />
            </div>
            <div class="flex flex-col justify-center px-4">
              <div class="text-lg font-semibold">Apply</div>
              <div class="text-2xl">0</div>
            </div>
          </div>

          <!-- Interview Card -->
          <div class="flex border border-black w-full max-w-[230px] h-[83px]">
            <div class="flex items-center justify-center w-[88px] h-full border-r border-black/20">
              <img src="{{ asset('assets/photos/dashboard/applicant-dashboard/image_136.png') }}" alt="Interview icon" class="w-[60px] h-[60px]" />
            </div>
            <div class="flex flex-col justify-center px-4">
              <div class="text-lg font-semibold">Interview</div>
              <div class="text-2xl">0</div>
            </div>
          </div>

          <!-- On Offer Card -->
          <div class="flex border border-black w-full max-w-[230px] h-[83px]">
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
  </div>
</body>

</html>