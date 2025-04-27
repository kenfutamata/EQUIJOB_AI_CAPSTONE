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

  <main class="flex-grow flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl bg-white rounded-3xl shadow-md p-8 space-y-8">
      <h2 class="text-3xl font-semibold text-zinc-800 text-center">Sign in</h2>

      <!-- Email Input -->
      <div class="space-y-1">
        <label class="block text-stone-500 text-base">Email</label>
        <input type="text" class="w-full h-14 px-4 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-green-500"  placeholder="enter email: text@gmail.com"/>
      </div>

      <!-- Password Input -->
      <div class="space-y-1 relative">
        <label class="block text-stone-500 text-base">Password</label>
        <input type="password" class="w-full h-14 px-4 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="enter password"/>
        <!-- Hide Icon -->
        <div class="absolute right-4 top-[42px] flex items-center space-x-1 text-stone-500 text-lg cursor-pointer">
          <svg width="20" height="20" fill="#666666" fill-opacity="0.8" viewBox="0 0 24 24">
            <path d="M3 3l18 18M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
          </svg>
          <span class="text-sm">Hide</span>
        </div>
      </div>

      <!-- Remember me and Help -->
      <div class="flex justify-between items-center text-base">
        <a href="#" class="text-red-500 hover:underline">Forgot Password?</a>
      </div>

      <!-- Sign In Button -->
      <button class="w-full py-3.5 bg-blue-600 text-white text-lg rounded-full hover:bg-red-700 transition">Sign in</button>

      <!-- Sign up link -->
      <div class="text-center text-base">
        <span class="text-stone-500">Don’t have an account? </span>
        <a href="#roleModal" class="text-neutral-900 font-medium underline">Sign up</a>      
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

  // Optional: Close modal on outside click
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('roleModal');
    if (e.target === modal) closeModal();
  });
</script>
</main>
</body>
</html>
