<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Privacy Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-6">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white border-4 border-blue-400 rounded-lg p-8">
            <div class="space-y-6">
                <div>
                    <p class="text-gray-800 text-base">Hello!</p>
                </div>
                <div>
                    <p class="text-gray-700">
                        Privacy Policy
                        <br>
                        At EQUIJOB, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information.
                        <br>
                        Information Collection: We collect personal information such as your name, email address, and job-related details when you register or use our services.
                        <br>
                        Use of Information: We use your information to provide and improve our services, communicate with you, and personalize your experience on our platform.
                        <br>
                        Data Security: We implement industry-standard security measures to protect your personal information from unauthorized access,
                        disclosure, alteration, or destruction.
                        <br>
                        Cookies: Our website may use cookies to enhance your browsing experience. You can choose to accept or decline cookies through your browser settings.
                        <br>
                        Third-Party Disclosure: We do not sell, trade, or otherwise transfer your personal information
                        to outside parties without your consent, except as required by law or to trusted third parties who assist us in operating our website and conducting our business.
                        <br>
                    </p>
                </div>

                <div>
                    <br>
                    <p class="font-bold text-gray-800">{{$maildata['jobProviderFirstName']}} {{$maildata['jobProviderLastName']}}</p>
                    <p class="text-blue-600">Job Provider - {{$maildata['companyName']}}</p>
                </div>
                <div class="text-center text-sm text-gray-500 mt-8">
                    &copy; 2025 EQUIJOB
                </div>
            </div>
        </div>
    </div>
</body>

</html>