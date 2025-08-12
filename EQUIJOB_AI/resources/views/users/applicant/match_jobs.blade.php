<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EQUIJOB - AI Job Matching</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}">
</head>
<body class="bg-[#FCFDFF] min-h-screen">
  <!-- Sidebar -->
  <div class="fixed top-0 left-0 w-[234px] h-full z-40 bg-[#c3d2f7]">
    <x-applicant-sidebar />
  </div>
  <!-- Topbar -->
  <div class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200">
    <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
  </div>

  <div class="ml-[234px] pt-20 px-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-6">
      <span class="text-gray-800">AI </span>
      <span class="text-blue-600">Job Matching</span>
    </h1>
  </div>

  <div class="ml-[234px] pt-6 flex justify-center items-start min-h-screen px-1">
    <div class="bg-white max-w-xl w-full rounded-[30px] border border-gray-300 p-8 shadow-md text-center">
      <h1 class="text-3xl font-semibold mb-6">Upload Resume</h1>
      
      @if ($errors->any() || session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
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
        <label for="resume" class="inline-block cursor-pointer bg-[#306CFE] text-black font-semibold text-lg py-4 px-8 rounded-xl hover:bg-blue-700 hover:text-white transition duration-200">
          Browse File
        </label>
        <input type="file" name="resume" id="resume" class="hidden" onchange="showFileName()" required />
        <p id="fileNameDisplay" class="mt-4 text-gray-600 text-sm italic"></p>
        <button type="submit" class="mt-8 bg-[#306CFE] text-black font-semibold text-lg py-4 px-12 rounded-xl hover:bg-blue-700 hover:text-white transition duration-200">
          Submit
        </button>
      </form>
    </div>
  </div>

  <script>
    function showFileName() {
      const input = document.getElementById('resume');
      const display = document.getElementById('fileNameDisplay');
      display.textContent = input.files.length > 0 ? `Selected file: ${input.files[0].name}` : '';
    }
  </script>
</body>
</html>