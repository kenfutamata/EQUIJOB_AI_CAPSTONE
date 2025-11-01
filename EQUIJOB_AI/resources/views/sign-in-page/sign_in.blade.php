<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Sign In</title>
  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('assets/sign-in/js/sign_in.js') }}" defer></script>

</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
  <x-landing-page-navbar />
  @if(session('Success'))
  <div id="notification-bar"
    class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 opacity-100 transition-opacity duration-500 ease-in-out">
    {{ session('Success') }}
  </div>
  @elseif(session('error'))
  <div id="notification-bar"
    class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 opacity-100 transition-opacity duration-500 ease-in-out">
    {{ session('error') }}
  </div>
  @endif
  <div class="flex-grow flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg space-y-6">
      <h2 class="text-2xl font-bold text-center">Sign in</h2>

      <form id="signInForm" method="POST" action="{{route('login')}}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        <div>
          <label class="block text-gray-600 text-sm mb-1">Email</label>
          <input type="text" class="w-full h-12 px-4 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter Email" id="email" name="email" required />
          @error('email')
          <div class="text-red text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="relative">
          <label class="block text-gray-600 text-sm mb-1">Password</label>
          <input type="password" class="w-full h-12 px-4 pr-12 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your password" id="password" name="password" required />
          @error('password')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="text-right">
          <a href="{{route('forgot-password')}}" class="text-sm text-red-500 hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full h-12 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition text-lg font-semibold">Sign in</button>
      </form>

      <div class="text-center text-sm text-gray-600">
        Don’t have an account?
        <a href="#" onclick="document.getElementById('roleModal').classList.remove('hidden')" class="text-indigo-600 font-medium hover:underline">Sign up</a>
      </div>
    </div>
  </div>

  <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
      <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-800">Job Applicant or Job Provider?</h3>
        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
      </div>
      <div class="space-y-4">
        <div class="space-y-4">
          <a href="{{ route('sign-up-applicant') }}" class="w-full block text-center py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-base font-medium">
            I am a Job Applicant
          </a>

          <a href="{{ route('sign-up-job-provider') }}" class="w-full block text-center py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-base font-medium">
            I am a Job Provider
          </a>
        </div>
      </div>
    </div>
  </div>
  <x-footer />

</body>

</html>