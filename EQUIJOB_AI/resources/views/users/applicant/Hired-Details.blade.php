<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hired Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-6">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white border-4 border-blue-400 rounded-lg p-8">
            <div class="space-y-6">
                <div>
                    <p class="text-gray-800 text-base">Hello {{$maildata['firstName']}} {{$maildata['lastName']}}</p>
                </div>
                <div>
                    <p class="text-gray-700">
                        Welcome to {{$maildata['companyName']}} and congratulations on being hired for the position of "{{$maildata['position']}}".
                        <br>
                        We will update you with the next steps in the hiring process. 
                        <br>
                        Please contact the job provider for any questions or concerns regarding your employment.
                        <br>
                        Email Account: {{$maildata['jobProviderEmail']}}
                        <br>
                        Phone Number: {{$maildata['jobProviderPhone']}}
                        <br>
                        We are looking forward for your work with you in the future.
                        <br>
                        Thank you and welcome to {{$maildata['companyName']}}!
                    </p>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Please Do not Reply on this Email.</p>
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