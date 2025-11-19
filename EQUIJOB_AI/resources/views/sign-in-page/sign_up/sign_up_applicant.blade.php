<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Sign Up</title>
  <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
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
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="First Name" id="firstName" name="firstName" value="{{ old('firstName') }}" pattern="[A-Za-z\s]+" required>
          @error('firstName')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Last Name -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Last Name</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Last Name" id="lastName" name="lastName" value="{{ old('lastName') }}" pattern="[A-Za-z\s]+" required>
          @error('lastName')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Email</label>
          <input type="email" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Email" id="email" name="email" value="{{ old('email') }}" required>
          @error('email')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Phone Number -->
        <div class="flex flex-col">
          <label class="text-stone-500 text-base mb-1">Phone Number</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Phone Number" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" pattern="^(\+?\d{1,3})?[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}[-.\s()]?\d{3,4}$" required>
          @error('phoneNumber')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Date of Birth -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Date of Birth</label>
          <input type="date" class="h-14 px-4 rounded-xl border border-stone-300" id="dateOfBirth" name="dateOfBirth" value="{{ old('dateOfBirth') }}" required max="{{date('Y-m-d')}}">
          @error('dateOfBirth')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Gender -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Gender</label>
          <select class="h-14 px-4 rounded-xl border border-stone-300" id="gender" name="gender" required>
            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
          </select>
          @error('gender')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Address -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Address</label>
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="Address" id="address" name="address" value="{{ old('address') }}" required>
          @error('address')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Province -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Province</label>
          <select id="province" name="provinceId" class="h-14 px-4 rounded-xl border border-stone-300" required>
            <option value="">Select Province</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}" {{ old('provinceId') == $province->id ? 'selected' : '' }}>
                    {{ $province->provinceName }}
                </option>
            @endforeach
          </select>
          @error('province_id')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- City -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">City</label>
          <select id="city" name="cityId" class="h-14 px-4 rounded-xl border border-stone-300" required>
            <option value="">Select City</option>
            @if(old('provinceId') && $cities ?? false)
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ old('cityId') == $city->id ? 'selected' : '' }}>
                        {{ $city->cityName }}
                    </option>
                @endforeach
            @endif
          </select>
          @error('cityId')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Type of Disability -->
        <div class="flex flex-col relative">
          <label class="text-stone-500 text-base mb-1">Type of Disability</label>
          <select class="h-14 px-4 rounded-xl border border-stone-300" id="typeOfDisability" name="typeOfDisability" required>
            <option value="" disabled {{ old('typeOfDisability') ? '' : 'selected' }}>Select Disability Type</option>
            <option value="Deaf or Hard of Hearing" {{ old('typeOfDisability') == 'Deaf or Hard of Hearing' ? 'selected' : '' }}>Deaf or Hard of Hearing</option>
            <option value="Intellectual Disability" {{ old('typeOfDisability') == 'Intellectual Disability' ? 'selected' : '' }}>Intellectual Disability</option>
            <option value="Learning Disability" {{ old('typeOfDisability') == 'Learning Disability' ? 'selected' : '' }}>Learning Disability</option>
            <option value="Mental Disability" {{ old('typeOfDisability') == 'Mental Disability' ? 'selected' : '' }}>Mental Disability</option>
            <option value="Physical Disability (Orthopedic)" {{ old('typeOfDisability') == 'Physical Disability (Orthopedic)' ? 'selected' : '' }}>Physical Disability (Orthopedic)</option>
            <option value="Psychosocial Disability" {{ old('typeOfDisability') == 'Psychosocial Disability' ? 'selected' : '' }}>Psychosocial Disability</option>
            <option value="Speech and Language Impairment" {{ old('typeOfDisability') == 'Speech and Language Impairment' ? 'selected' : '' }}>Speech and Language Impairment</option>
            <option value="Visual Disability" {{ old('typeOfDisability') == 'Visual Disability' ? 'selected' : '' }}>Visual Disability</option>
            <option value="Cancer (RA11215)" {{ old('typeOfDisability') == 'Cancer (RA11215)' ? 'selected' : '' }}>Cancer (RA11215)</option>
            <option value="Rare Disease (RA10747)" {{ old('typeOfDisability') == 'Rare Disease (RA10747)' ? 'selected' : '' }}>Rare Disease (RA10747)</option>
          </select>
          @error('typeOfDisability')
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
          <input type="text" class="h-14 px-4 rounded-xl border border-stone-300" placeholder="PWD ID format: 13-5416-000-0000123" id="pwdId" name="pwdId" value="{{ old('pwdId') }}" pattern="\d{2}-\d{4}-\d{3}-\d{7}" required>
          @error('pwdId')
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

        <!-- Terms & Conditions -->
        <div class="md:col-span-2 text-sm text-gray-600 mb-2">
          <label class="inline-flex items-start gap-3">
            <input type="checkbox" id="checkAgree" onchange="checkAgreement()" class="mt-1">
            <span>By signing up, you agree to our <a href="#" onclick="openAgreementModal(); return false;" class="text-blue-600 underline">Terms and Conditions and Privacy Policy</a>.</span>
          </label>
        </div>

        <!-- Submit & Back -->
        <div class="md:col-span-2 flex flex-col gap-4 pt-2">
          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold transition" id="sign_up_button">
            Sign Up
          </button>
          <a href="{{ route('sign-in') }}" class="w-full text-center bg-black hover:bg-gray-800 text-white py-4 rounded-xl font-semibold transition">
            Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>

  <x-footer />

  <!-- Agreement Modal -->
  <div id="agreementModal" role="dialog" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-10 space-y-10">
      <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold">Terms & Conditions</h3>
        <button onclick="closeAgreementModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
      </div>
      <div class="max-h-[60vh] overflow-y-auto text-gray-700 text-sm space-y-2">
        <p>By using our services, you agree to comply with and be bound by the following terms and conditions...</p>
      </div>
    </div>
  </div>

<script>
document.getElementById('province').addEventListener('change', async function() {
    const provinceId = this.value;
    const citySelect = document.getElementById('city');

    if (!provinceId) {
        citySelect.innerHTML = '<option value="">Select a Province</option>';
        return;
    }

    citySelect.innerHTML = '<option value="">Loading...</option>';

    try {
        const response = await fetch(`/cities/${provinceId}`);
        
        // This is now guaranteed to be the array of city objects.
        const cities = await response.json(); 

        citySelect.innerHTML = '<option value="">Select City</option>';

        if (cities && cities.length > 0) {
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.cityName; 
                citySelect.appendChild(option);
            });
        } else {
            citySelect.innerHTML = '<option value="">No cities found</option>';
        }
    } catch (err) {
        console.error('Error fetching cities:', err);
        citySelect.innerHTML = '<option value="">Error loading cities</option>';
    }
});
</script>

</body>
</html>
