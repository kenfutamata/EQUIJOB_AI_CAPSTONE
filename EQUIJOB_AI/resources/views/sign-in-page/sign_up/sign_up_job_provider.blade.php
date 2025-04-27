<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Provider Sign Up</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <!-- Navbar -->
  <x-landing-page-navbar />

  <!-- Main Content -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-lg p-10 w-full max-w-5xl">
      
      <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Sign Up</h2>

      <form action="#" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">First Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="First Name">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Last Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Last Name">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Email</label>
            <input type="email" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Email Address">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Phone Number</label>
            <input type="tel" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Phone Number">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Confirm Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirm Password">
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Company</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Company Name">
          </div>
        </div>

        <div>
          <label class="block mb-2 text-gray-600 text-sm font-medium">Company Logo</label>
          <div class="flex items-center space-x-4">
            <label class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-200 transition">
              Choose File
              <input type="file" class="hidden">
            </label>
            <span class="text-gray-500 text-sm">No file selected</span>
          </div>
        </div>

        <div class="flex flex-col gap-4 pt-6">
          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
            Sign Up
          </button>
          <a href="{{ route('sign-in') }}" class="w-full text-center bg-black hover:bg-gray-800 text-white py-3 rounded-lg font-semibold transition">
            Back to Login
          </a>
        </div>
      </form>

    </div>
  </div>

</body>
</html>
