<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Sign Up</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
  <script src="{{ asset('assets/sign-up/js/sign_up_applicant.js') }}" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <x-landing-page-navbar />

  @if ($message = Session::get('error'))
  <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
    {{ $message }}
  </div>
  @endif
  <div class="w-full min-h-screen flex justify-center items-center px-4 py-10">
    <div class="bg-white max-w-4xl w-full rounded-3xl shadow-lg p-8 md:p-12">
      <h2 class="text-3xl font-semibold text-zinc-800 text-center mb-8">Sign Up Job Applicant</h2>

      <form class="grid grid-cols-1 md:grid-cols-2 gap-6" action="{{route('sign-up-applicant-register')}}" method="post" enctype="multipart/form-data" onsubmit="return validateAgreement()">
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
        <div class="md:col-span-2 text-sm text-gray-600">
          <input type="checkbox" id="checkAgree" onchange="checkAgreement()"> By signing up, you agree to our <a href="" onclick="openAgreementModal(); return false;" class="text-blue-600 underline">Terms and Conditions and Privacy Policy</a>.
        </div>
        <br>
        <br>
        <div class="flex flex-col md:flex-row gap-4 mt-10">
          <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md text-base font-semibold hover:bg-blue-700 transition" id="sign_up_button">
            Sign Up
          </button>
          <a href="{{ route('sign-in') }}" class="w-full bg-black text-white py-3 rounded-md text-base font-semibold text-center hover:bg-gray-800 transition">
            Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>


  <!--Agreement modal-->
  <div id="agreementModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-10 space-y-10">
      <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold">Terms & Conditions</h3>
        <button onclick="closeAgreementModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
      </div>
      <div class="max-h-[60vh] overflow-y-auto">
        <p class="text-gray-700 text-sm">
          By using our services, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before proceeding.
        </p>
        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2 mt-4">
          Welcome to EQUIJOB a platform powered by AI that links job seekers
          with disabilities with inclusive employers.
          Job seekers using the Platform should be at least 18 years old or of legal working age, and employers must have the legal ability to hire employees.
          We provide job seekers with tools to help find jobs and we provide employers with tools to source candidates,
          but we cannot guarantee that candidates will get job offers or interview opportunities, and we cannot verify
          whether all employers will employ individuals or are inclusive.
        </ul>
        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2 mt-4">
          We encourage all users to act responsibly. Job seekers should not mislead the Platform with inaccurate information about their skills or job related experience,
          and employers must not mislead the Platform's users with inaccurate job descriptions.
          Job Providers must comply with labor laws and the laws of disability inclusion, and not seek candidates that discriminate against individuals with disabilities.
          Users must respect one another, and not partake in harm through the Platform, trolling, creating a fake account, or discrimination.
        </ul>
        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2 mt-4">
          Your privacy is very important to us. Sensitive information such as disability particulars
          will not be shared with your employer without permission.
          While we endeavor to protect your data, we can offer no guarantees of complete security.
        </ul>
        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2 mt-4">
          Lastly, we are not responsible for hiring disputes, employment outcomes, or concerns not made on the Platform.
          We can cancel or suspend accounts that breach these rules,
          and we reserve the right to revise these Terms from time to time.
          If you continue to use the Platform after those changes, you are agreeing to them.
        </ul>
        <p class="text-gray-700 text-sm mt-4">
          By clicking the check mark, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.
        </p>
      </div>
    </div>
      <x-footer />

</body>

</html>