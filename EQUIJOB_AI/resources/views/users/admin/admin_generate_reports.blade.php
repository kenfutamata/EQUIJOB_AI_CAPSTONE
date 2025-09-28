<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Administrator Generate Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 w-[234px] h-full z-40 bg-[#c3d2f7]">
        <x-admin-sidebar />
    </aside>

    <!-- Topbar -->
    <header
        class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200 shadow-sm">
        <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
    </header>

    <!-- Main Content -->
    <main class="ml-[234px] mt-[64px] p-6 min-h-screen">

        @foreach (['Success' => 'green', 'error' => 'red', 'Delete_Success' => 'green'] as $key => $color)
        @if (session($key))
        <div id="notification-bar"
            class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-{{ $color }}-500 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500">
            {{ session($key) }}
        </div>
        @endif
        @endforeach

        <!-- Page Header -->
        <div class="container mx-auto p-4 sm:p-8">
            <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-gray-800">Administrator Report</h1>

                <form method="GET" action="{{ url()->current() }}"
                    class="flex items-center gap-3 bg-white p-2 rounded-lg shadow-sm border">
                    <label for="month" class="text-sm font-medium text-gray-700">Select Month:</label>
                    <input type="month" id="month" name="month" value="{{ $selectedMonth }}"
                        class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                        View Report
                    </button>
                </form>
            </div>

            @if($applicantChartData || $jobProviderChartData || $hiredChartData)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Applicant Registered for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($applicantChartData)
                    <div class="mx-auto"><canvas id="applicantRegisteredChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No Applicants Registered for this month.</p>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Registered Job Providers for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($jobProviderChartData)
                    <div class="mx-auto"><canvas id="jobProviderChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No Job Providers registered for this month.</p>
                    @endif
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Hired Applicants for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($hiredChartData)
                    <div class="mx-auto"><canvas id="hiredApplicantsChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No Hired Applicants for this month.</p>
                    @endif
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Rejected Applicants for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($disapprovedChartData)
                    <div class="mx-auto"><canvas id="rejectedApplicantsChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No Rejected/Disapproved Applicants for this month.</p>
                    @endif
                </div>


            </div>
            @else
            <div class="bg-white p-6 rounded-lg shadow-md border max-w-4xl mx-auto text-center py-12">
                <h3 class="text-xl font-semibold text-gray-700">No Data Available</h3>
                <p class="text-gray-500 mt-2">{{ $errorMessage ?? 'Please select a different month.' }}</p>
            </div>
            @endif
        </div>
    </main>


    <!-- Charts Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const notifBar = document.getElementById('notification-bar');
            if (notifBar) {
                setTimeout(() => {
                    notifBar.style.opacity = '0';
                    setTimeout(() => notifBar.remove(), 500);
                }, 3000);
            }

            @if($applicantChartData)
            const applicantCtx = document.getElementById('applicantRegisteredChart').getContext('2d');
            const applicantData = @json($applicantChartData);
            new Chart(applicantCtx, {
                type: 'line',
                data: {
                    labels: applicantData.labels,
                    datasets: [{
                        label: 'New Applicants Registered',
                        data: applicantData.values,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {}
            });
            @endif

            @if($jobProviderChartData)
            const providerCtx = document.getElementById('jobProviderChart').getContext('2d');
            const providerData = @json($jobProviderChartData);
            new Chart(providerCtx, {
                type: 'line',
                data: {
                    labels: providerData.labels,
                    datasets: [{
                        label: 'New Job Providers Registered',
                        data: providerData.values,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    /* ... your chart options ... */
                }
            });
            @endif

            // --- Chart 3: Hired Applicants ---
            @if($hiredChartData)
            const hiredCtx = document.getElementById('hiredApplicantsChart').getContext('2d');
            const hiredData = @json($hiredChartData);
            new Chart(hiredCtx, {
                type: 'bar',

                data: {
                    labels: hiredData.labels,
                    datasets: [{
                        label: 'Applicants Hired',
                        data: hiredData.values,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {}
            });
            @endif

            @if($disapprovedChartData)
            const rejectedCtx = document.getElementById('rejectedApplicantsChart').getContext('2d');
            const rejectedData = @json($disapprovedChartData);
            new Chart(rejectedCtx, {
                type: 'bar',
                data: {
                    labels: rejectedData.labels,
                    datasets: [{
                        label: 'Applicants Rejected/Disapproved',
                        data: rejectedData.values,
                        backgroundColor: 'rgba(255, 0, 0, 0.6)',
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                }
            });
            @endif

        });
    </script>

</body>

</html>