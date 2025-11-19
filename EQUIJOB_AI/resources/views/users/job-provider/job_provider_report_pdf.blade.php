<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Performance Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Define the local 'Inter' font for the PDF generator */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url("{{ storage_path('fonts/Inter-Regular.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            src: url("{{ storage_path('fonts/Inter-Medium.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            src: url("{{ storage_path('fonts/Inter-SemiBold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            src: url("{{ storage_path('fonts/Inter-Bold.ttf') }}") format('truetype');
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Lighter slate for better contrast */
            color: #334155; /* Darker slate for text */
            padding: 2rem;
        }
    </style>
</head>

<body class="text-slate-800">

    <header class="mb-8">
         <x-report-topbar />
        <h1 class="text-4xl font-semibold text-[#113882] mb-1">Monthly Performance Report</h1>
        <h2 class="text-2xl font-medium text-slate-600">{{$user->companyName}}</h2>
        <p class="text-base text-slate-500 mt-1">As of {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
    </header>

    <main>
        <h2 class="text-2xl font-semibold text-slate-700 mt-10 mb-4 border-b pb-2">Summary</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <section class="bg-white p-6 rounded-xl shadow-md flex flex-col justify-between">
                <h3 class="text-base font-medium text-slate-500 mb-4">Total Applicants</h3>
                <p class="text-5xl font-bold text-[#113882] leading-none">{{$jobApplicationCount}}</p>
            </section>
            <section class="bg-white p-6 rounded-xl shadow-md flex flex-col justify-between">
                <h3 class="text-base font-medium text-slate-500 mb-4">For Interview</h3>
                <p class="text-5xl font-bold text-[#113882] leading-none">{{$jobApplicantInterviewCount}}</p>
            </section>
            <section class="bg-white p-6 rounded-xl shadow-md flex flex-col justify-between">
                <h3 class="text-base font-medium text-slate-500 mb-4">Hired</h3>
                <p class="text-5xl font-bold text-[#113882] leading-none">{{$jobApplicantHiredCount}}</p>
            </section>
            <section class="bg-white p-6 rounded-xl shadow-md flex flex-col justify-between">
                <h3 class="text-base font-medium text-slate-500 mb-4">Rejected</h3>
                <p class="text-5xl font-bold text-[#113882] leading-none">{{$jobApplicantRejectedCount}}</p>
            </section>
            <section class="bg-white p-6 rounded-xl shadow-md flex flex-col justify-between">
                <h3 class="text-base font-medium text-slate-500 mb-4">Total Job Postings</h3>
                <p class="text-5xl font-bold text-[#113882] leading-none">{{$jobPostingCount}}</p>
            </section>
        </div>

        <!-- Section for Charts -->
        <h2 class="text-2xl font-semibold text-slate-700 mt-10 mb-4 border-b pb-2">Visualizations</h2>
        <div class="mt-2">
            @if((isset($trendsChartImageUrl) && $trendsChartImageUrl) || (isset($ratingsChartImageUrl) && $ratingsChartImageUrl))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Application Trends Doughnut Chart --}}
                    @if(isset($trendsChartImageUrl) && $trendsChartImageUrl)
                        <section class="bg-white p-6 rounded-xl shadow-md">
                            <h3 class="text-xl font-semibold text-slate-700 border-b border-slate-200 pb-3 mb-4">Application Status Trends</h3>
                            <div class="flex justify-center items-center p-4">
                                <img src="{{ $trendsChartImageUrl }}" alt="Application Trends Chart" style="max-width: 100%; height: auto;">
                            </div>
                        </section>
                    @endif

                    @if(isset($ratingsChartImageUrl) && $ratingsChartImageUrl)
                        <section class="bg-white p-6 rounded-xl shadow-md">
                            <h3 class="text-xl font-semibold text-slate-700 border-b border-slate-200 pb-3 mb-4">Feedback Ratings</h3>
                            <div class="flex justify-center items-center p-4">
                                <img src="{{ $ratingsChartImageUrl }}" alt="Feedback Ratings Chart" style="max-width: 100%; height: auto;">
                            </div>
                        </section>
                    @endif
                </div>
            {{-- If there is an error message, display it instead of empty chart space --}}
            @elseif(isset($errorMessage))
                <div class="bg-slate-50 border border-slate-200 text-slate-600 p-6 rounded-xl text-center">
                    <p class="font-semibold text-lg">No Chart Data Available</p>
                    <p class="mt-2">{{ $errorMessage }}</p>
                </div>
            @endif
        </div>
    </main>

</body>
</html>