<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Reset your password</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <script src="{{ asset('assets/sign-in/js/sign_in.js') }}"></script>

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
            <h2 class="text-2xl font-bold text-center">Reset your Password</h2>

            <form id="resetPasswordForm" method="POST" action="{{route('forgot-password.update-password')}}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Email</label>
                    <input type="text" class="w-full h-12 px-4 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" value="{{$email}}" id="email" name="email" readonly />
                </div>
                <div class="relative">
                    <label class="block text-gray-600 text-sm mb-1">Password</label>
                    <input type="password" class="w-full h-12 px-4 pr-12 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your password" id="password" name="password" required />
                    @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                    <label class="block text-gray-600 text-sm mb-1">Password Confirmation</label>
                    <input type="password" class="w-full h-12 px-4 pr-12 rounded-lg bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Re-Enter your password" id="password_confirmation" name="password_confirmation" required />
                    @error('password_confirmation')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="w-full h-12 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition text-lg font-semibold">Update Password</button>
            </form>

        </div>
    </div>


</body>

</html>