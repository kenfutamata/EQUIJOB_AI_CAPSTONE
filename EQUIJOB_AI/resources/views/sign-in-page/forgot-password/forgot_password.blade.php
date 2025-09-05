<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EQUIJOB-Forgot Password</title>
    <script src="{{ asset('assets/sign-in/forgot-password/js/forgot_password.js') }}"></script>

    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo (2).png')}}" />
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <x-landing-page-navbar />
    @if(session('Success'))
    <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ session('Success') }}
    </div>
    @elseif(session('error'))
    <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ session('error') }}
    </div>
    @elseif($errors->any())
    <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
        {{ $errors->first() }}
    </div>
    @endif
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 bg-[#FCFDFF] flex justify-center items-center">
        <div class="max-w-6xl mx-auto space-y-8">

            <h1 class="font-audiowide text-4xl md:text-5xl text-gray-800 text-center">
                <span class="text-[#25324B]">Forgot Password </span>
            </h1>
            <div class="w-full max-w-md sm:max-w-xl md:max-w-3xl lg:max-w-5xl bg-white shadow-md border border-gray-300 rounded-lg p-6 sm:p-12 md:p-16 lg:p-24 mx-auto">
                <label for="label_email_address" class="block text-lg sm:text-xl font-medium text-gray-700 mb-4 text-center">
                    Enter your Email Address
                </label>
                <form action="{{route('forgot-password.validate-email')}}" method="get" class="space-y-6">
                    @csrf
                    <input type="email" id="email" name="email"
                        class="w-full border border-gray-400 rounded-md px-4 py-3 sm:px-6 sm:py-4 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Enter your Email Address" required />
                    <br>
                    <br>
                    <button class="bg-[#0073E6] text-white text-base sm:text-xl font-medium px-6 sm:px-10 py-3 sm:py-4 rounded-xl flex justify-center w-full sm:w-auto">
                        Send Verification Email
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>