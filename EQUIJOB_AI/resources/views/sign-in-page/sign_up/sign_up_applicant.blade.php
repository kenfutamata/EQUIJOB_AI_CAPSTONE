<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Sign Up</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <x-landing-page-navbar />

  <!-- Registration Form Section -->
  <!-- Error Message -->
  @if ($message = Session::get('error'))
  <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
    {{ $message }}
  </div>
  @endif
  <div class="w-full min-h-screen flex justify-center items-center px-4 py-10">
    <div class="bg-white max-w-4xl w-full rounded-3xl shadow-lg p-8 md:p-12">
      <h2 class="text-3xl font-semibold text-zinc-800 text-center mb-8">Sign Up</h2>

      <form class="grid grid-cols-1 md:grid-cols-2 gap-6" action="{{route('sign-up-applicant-register')}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <!-- First Name -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">First Name</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="First Name" id="first_name" name="first_name" pattern="[A-Za-z\s]+" required>
          @error('first_name')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Last Name -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Last Name</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Last Name" id="last_name" name="last_name" pattern="[A-Za-z\s]+" required>
          @error('last_name')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Email</label>
          <input type="email" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Email" id="email" name="email" required>
          @error('email')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Phone Number -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Phone Number</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Phone Number" id="phone_number" name="phone_number" pattern="^(\+?\d{1,3})?[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}$"
            required>
          @error('phone_number')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Date of Birth -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Date of Birth</label>
          <input type="date" class="h-14 px-4 rounded-xl border border-stone-300" id="date_of_birth" name="date_of_birth" required max="{{date('Y-m-d')}}" required>
          @error('date_of_birth')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>
        <!-- Gender -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Gender</label>
          <select class="h-14 px-4 rounded-xl border border-stone-300" id="gender" name="gender" required>
            <option selected disabled>Select Gender</option>
            <option>Male</option>
            <option>Female</option>
          </select>
          @error('gender')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>
        <!--Address-->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Address</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Address" id="address" name="address" required>
          @error('address')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>
        <!-- Type of Disability -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Type of Disability</label>
          <select class="h-14 px-4 rounded-xl border border-stone-300" id="type_of_disability" name="type_of_disability" required>
            <option selected disabled>Select Disability Type</option>
            <option>Physical</option>
            <option>Visual</option>
            <option>Hearing</option>
            <option>Intellectual</option>
          </select>
          @error('disability_type')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Password</label>
          <input type="password" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Password" id="password" name="password" required>
          @error('password')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Confirm Password</label>
          <input type="password" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" required>
          @error('password_confirmation')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- PWD ID -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">PWD ID</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="PWD ID format: 123-456-789" id="pwd_id" name="pwd_id" pattern="\d{3}-\d{3}-\d{3}" required>
          @error('pwd_id')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Upload PWD Card -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Upload PWD Card</label>
          <input type="file" class="h-14 px-4 py-2 rounded-xl border border-stone-300" id="upload_pwd_card" name="upload_pwd_card" accept="image/*" required>
          @error('upload_pwd_card')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>
        <br>
        <br>
        <!-- Buttons -->
        <div class="flex flex-col md:flex-row gap-4 mt-10">
          <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md text-base font-semibold hover:bg-blue-700 transition">
            Sign Up
          </button>
          <a href="{{ route('sign-in') }}" class="w-full bg-black text-white py-3 rounded-md text-base font-semibold text-center hover:bg-gray-800 transition">
            Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>

</body>

</html>