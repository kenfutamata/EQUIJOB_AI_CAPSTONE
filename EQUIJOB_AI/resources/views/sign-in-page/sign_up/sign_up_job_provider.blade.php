<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Provider Sign Up</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('assets/sign-up/js/sign_up_job_provider.js') }}" defer></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
  <x-landing-page-navbar />
  @if ($message = Session::get('error'))
  <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
    {{ $message }}
  </div>
  @endif
  <!-- Main Content -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-lg p-10 w-full max-w-5xl">

      <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Sign Up Job Provider</h2>

      <form action="{{route('sign-up-job-provider-register')}}" method="POST" class="space-y-6" enctype="multipart/form-data" onsubmit="return validateAgreement()">
        @csrf
        @method('POST')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">First Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="First Name" id="firstName" name="firstName" pattern="[A-Za-z\s]+" value="{{ old('firstName') }}" required>
            @error('firstName')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Last Name</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Last Name" id="lastName" name="lastName" pattern="[A-Za-z\s]+" value="{{ old('lastName') }}" required>
            @error('lastName')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Email</label>
            <input type="email" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Email Address" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Phone Number</label>
            <input type="tel" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Phone Number" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" required>
            @error('phoneNumber')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password" id="password" name="password" required>
            @error('password')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Confirm Password</label>
            <input type="password" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" required>
            @error('password_confirmation')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div>
            <label class="block mb-2 text-gray-600 text-sm font-medium">Company</label>
            <input type="text" class="w-full rounded-xl border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Company Name" id="companyName" name="companyName" value="{{ old('companyName') }}" required>
            @error('companyName')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div>
          <label class="block mb-2 text-gray-600 text-sm font-medium">Company Logo</label>
          <div class="flex flex-col">
            <input type="file" class="h-14 px-4 py-2 rounded-xl border border-stone-300" id="companyLogo" name="companyLogo" accept="image/*" required>
            @error('companyLogo')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div>
          <label class="block mb-2 text-gray-600 text-sm font-medium">Business Permit</label>
          <div class="flex flex-col">
            <input type="file" class="h-14 px-4 py-2 rounded-xl border border-stone-300" id="businessPermit" name="businessPermit" accept="image/*, application/pdf " required>
            @error('businessPermit')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="md:col-span-2 text-sm text-gray-600">
          <input type="checkbox" id="checkAgree" onchange="checkAgreement()"> By signing up, you agree to our <a href="" onclick="openAgreementModal(); return false;" class="text-blue-600 underline">Terms and Conditions and Privacy Policy</a>.
        </div>
        <div class="flex flex-col gap-4 pt-6">
          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition" id="sign_up_button">
            Sign Up
          </button>
          <a href="{{ route('sign-in') }}" class="w-full text-center bg-black hover:bg-gray-800 text-white py-3 rounded-lg font-semibold transition">
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