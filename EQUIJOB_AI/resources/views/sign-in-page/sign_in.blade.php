<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Sign In</title>
  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo (2).png')}}" />
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <!-- Navbar component -->
  <x-landing-page-navbar />
    <!-- Error Message -->
    @if ($message = Session::get('error'))
    <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
    {{ $message }}
    @endif
    </div>
  <div class="flex-grow flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg space-y-6">
    <h2 class="text-2xl font-bold text-center">Sign in</h2>

    <form id="signInForm" method="POST" action="{{route('login')}}" class="space-y-6" enctype="multipart/form-data">
      @csrf
      @method('POST')
      <!-- Email Input -->
      <div>
        <label class="block text-gray-600 text-sm mb-1">Email</label>
        <input name="email" type="text" class="w-full h-12 px-4 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter Email" id="email" name="email" required/>
        @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
      </div>

      <!-- Password Input -->
      <div class="relative">
        <label class="block text-gray-600 text-sm mb-1">Password</label>
        <input id="password" name="password" type="password" class="w-full h-12 px-4 pr-12 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your password" id="email" name="email" required/>
        <!-- Toggle visibility -->
        <button type="button" onclick="togglePassword()" class="absolute right-3 top-9 text-gray-500 text-sm flex items-center space-x-1">
          <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-10-7-10-7a18.98 18.98 0 013.75-5.25M21 12s-3 7-10 7c-.62 0-1.23-.057-1.82-.167M9.53 9.53A3.001 3.001 0 0114.47 14.47" />
          </svg>
          <span>Hide</span>
        </button>
        @error('password')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="text-right">
        <a href="#" class="text-sm text-red-500 hover:underline">Forgot Password?</a>
      </div>

      <button type="submit" class="w-full h-12 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition text-lg font-semibold">Sign in</button>
    </form>

    <div class="text-center text-sm text-gray-600">
      Don’t have an account?
      <a href="#" onclick="document.getElementById('roleModal').classList.remove('hidden')" class="text-indigo-600 font-medium hover:underline">Sign up</a>
    </div>
  </div>
</div>

 <!-- Modal Background -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <!-- Modal Box -->
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-6">
    <div class="flex justify-between items-center">
      <h3 class="text-xl font-semibold text-gray-800">Select Your Role</h3>
      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>
    <div class="space-y-4">
    <div class="space-y-4">
      <a href="{{ route('sign-up-applicant') }}" class="w-full block text-center py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-base font-medium">
        I am a Job Seeker
      </a>
      
      <a href="{{ route('sign-up-job-provider') }}" class="w-full block text-center py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-base font-medium">
        I am a Job Provider
      </a>
    </div>
    </div>
  </div>
</div>
<script>
  //modall script
  // Open modal
  document.querySelector('a[href="#roleModal"]').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('roleModal').classList.remove('hidden');
  });

  // Close modal
  function closeModal() {
    document.getElementById('roleModal').classList.add('hidden');
  }

  window.addEventListener('click', function(e) {
    const modal = document.getElementById('roleModal');
    if (e.target === modal) closeModal();
  });

  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
  }
</script>
</main>
</body>
</html>
