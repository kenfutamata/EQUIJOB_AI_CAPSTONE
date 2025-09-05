<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/photos/landing_page/equijob_logo (2).png') }}" />
    <title>Password Update Request Sent</title>
</head>

<body>
    <script src="https://cdn.tailwindcss.com"></script>
    <x-landing-page-navbar />

    <div class="popup-component flex justify-center items-center min-h-screen px-4">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6 mx-auto text-center">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-20 h-20 fill-blue-500 mx-auto mb-6" viewBox="0 0 60 60">
                <circle cx="30" cy="30" r="29" />
                <path fill="#fff"
                    d="m24.262 42.07-6.8-6.642a1.534 1.534 0 0 1 0-2.2l2.255-2.2a1.621 1.621 0 0 1 2.256 0l4.048 3.957 11.353-17.26a1.617 1.617 0 0 1 2.2-.468l2.684 1.686a1.537 1.537 0 0 1 .479 2.154L29.294 41.541a3.3 3.3 0 0 1-5.032.529z" />
            </svg>

            <div class="mt-12">
                <h3 class="text-gray-800 text-2xl font-bold">Request sent!</h3>
                <p class="text-sm text-gray-600 mt-3">Please check your email for password verification</p>
                <br>
                <a
                    href="{{route('landing-page')}}" class="px-10 py-2.5 mt-10 w-full rounded-md text-white text-sm font-semibold tracking-wide border-none outline-none bg-blue-500 hover:bg-blue-600">
                    OK
                </a>
            </div>
        </div>
    </div>
</body>

</html>