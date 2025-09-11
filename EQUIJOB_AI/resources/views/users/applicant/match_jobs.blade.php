<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EQUIJOB - AI Job Matching</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
</head>

<body x-data="{ sidebarOpen: false }" class="bg-[#FCFDFF] min-h-screen">

  <div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-30 lg:hidden">
  </div>

  <aside
    x-show="sidebarOpen"
    x-transition:enter="transition transform duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition transform duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 w-[234px] bg-white z-40 lg:hidden shadow-lg flex flex-col overflow-y-auto">
    <div class="flex flex-col h-full bg-[#c7d4f8]">
      <div class="flex justify-end p-4">
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

  <aside class="hidden lg:flex lg:flex-col lg:w-[234px] lg:fixed lg:inset-y-0 lg:z-20">
    <div class="flex flex-col h-full bg-[#c7d4f8]">
      <div class="flex-1 overflow-y-auto min-h-0">
        <x-applicant-sidebar />
      </div>
    </div>
  </aside>

  <div class="flex flex-col flex-1 lg:ml-[234px] h-screen">
    <header class="fixed top-0 left-0 right-0 lg:left-[234px] h-16 z-10 bg-white border-b border-gray-200 flex items-center">
      <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-4 text-gray-600 hover:text-gray-900 focus:outline-none">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
      <div class="flex-1">
        <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
      </div>
    </header>

    <main class="mt-16 overflow-y-auto h-[calc(100vh-4rem)] p-4 sm:p-8">
      <h1 class="text-4xl font-bold text-gray-800 mb-6">
        <span class="text-gray-800">AI </span>
        <span class="text-blue-600">Job Matching</span>
      </h1>

      <div class="flex justify-center items-start w-full">
        <div class="bg-white max-w-xl w-full rounded-[30px] border border-gray-300 p-6 md:p-8 shadow-md text-center">
          <h2 class="text-3xl font-semibold mb-6">Upload Resume</h2>

          @if ($errors->any() || session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 text-left" role="alert">
            <strong class="font-bold">Oops! Something went wrong.</strong>
            <ul class="mt-2 list-disc list-inside">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
              @if(session('error'))
              <li>{{ session('error') }}</li>
              @endif
            </ul>
          </div>
          @endif

          <form action="{{ route('applicant-match-jobs-upload-resume') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
            @csrf
            <label for="resume" class="inline-block cursor-pointer bg-[#306CFE] text-white font-semibold text-lg py-4 px-8 rounded-xl hover:bg-blue-700 transition duration-200">
              Browse File
            </label>
            <input type="file" name="resume" id="resume" class="hidden" onchange="showFileName()" required />
            <p id="fileNameDisplay" class="mt-4 text-gray-600 text-sm italic"></p>
            <button type="submit" class="mt-8 bg-[#306CFE] text-white font-semibold text-lg py-4 px-12 rounded-xl hover:bg-blue-700 transition duration-200">
              Submit
            </button>
          </form>
        </div>
      </div>
    </main>

  </div>


  <script src="{{ asset('assets/applicant/ai-job-matching/js/match_jobs.js') }}"></script>

</body>

</html>