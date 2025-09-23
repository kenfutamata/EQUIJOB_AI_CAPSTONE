<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reminder: Interview Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-6">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white border-4 border-blue-400 rounded-lg p-8">
            <div class="space-y-6">
                <div>
                    <p class="text-gray-800 text-base">Hello {{$jobApplication->jobPosting->companyName}}</p>
                </div>
                <div>
                    <p class="text-gray-700">
                        Please be Informed that your Interview with {{$jobApplication->applicant->firstName}} {{$jobApplication->applicant->lastName}} for the 
                        <br>
                        Position for {{$jobApplication->jobPosting->position}} will be tomorrow. Here are the interview details:
                            <br>
                            INTERVIEW DETAILS:
                            <br>
                            Interview Date: {{$jobApplication->interviewDate->format('F j, Y, g:i A')}}
                            <br>
                            Google Meet Link: {{$jobApplication->interviewLink}}
                            <br>
                            Applicant Name: {{$jobApplication->applicant->firstName}} {{$jobApplication->applicant->lastName}}
                    </p>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Please Do not Reply on this Email.</p>
                    <br>
                </div>
                <div class="text-center text-sm text-gray-500 mt-8">
                    &copy; 2025 EQUIJOB
                </div>
            </div>
        </div>
    </div>
</body>

</html>