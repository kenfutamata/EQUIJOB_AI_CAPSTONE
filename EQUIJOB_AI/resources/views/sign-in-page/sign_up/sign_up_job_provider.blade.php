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
    <!-- Error Message -->
  @if ($message = Session::get('error'))
    <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
    {{ $message }}
    </div>
  @endif
  <!-- Main Content -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-lg p-10 w-full max-w-5xl">
      
      <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Sign Up</h2>

      <form action="{{route('sign-up-job-provider-register')}}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">First Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="First Name" id="first_name" name="first_name" pattern="[A-Za-z\s]+" required>
          </div>
          @error('first_name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Last Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Last Name" id="last_name" name="last_name" pattern="[A-Za-z\s]+" required>
          </div>
          @error('last_name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Email</label>
            <input type="email" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Email Address" id="email" name="email" required>
          </div>
          @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Phone Number</label>
            <input type="tel" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Phone Number" id="phone_number" name="phone_number" required>
          </div>
          @error('first_name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password" id="password" name="password" required>
          </div>
          @error('password')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Confirm Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" required>
          </div>
          @error('password_confirmation')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Company</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Company Name" id="company_name" name="company_name" pattern="[A-Za-z\s]+" required>
          </div>
          @error('company_name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
              @enderror
        </div>

        <div>
  <label class="block mb-2 text-gray-600 text-sm font-medium">Company Logo</label>
      <div class="flex flex-col">
      <input type="file" class="h-14 px-4 py-2 rounded-xl border border-stone-300" id="company_logo" name="company_logo" accept="image/*" required>
      @error('upload_pwd_card')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
      @enderror
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
