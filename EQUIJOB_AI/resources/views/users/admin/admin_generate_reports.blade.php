<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Administrator Reports</title>
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
    <header class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200 shadow-sm">
        <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
    </header>

    <!-- Main Content -->
    <main class="ml-[234px] mt-[64px] p-6 min-h-screen">

        @foreach (['Success' => 'green', 'error' => 'red'] as $key => $color)
            @if (session($key))
            <div id="notification-bar" class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-{{ $color }}-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500">
                {{ session($key) }}
            </div>
            @endif
        @endforeach

        <!-- Page Header & Filters -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Administrator Report</h1>
                <p class="text-gray-500">System-wide statistics for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- FORM WITH BOTH INPUTS -->
                <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-4 bg-white p-2 rounded-lg shadow-sm border">
                    <div>
                        <label for="month" class="text-sm font-medium text-gray-700">Month:</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}" class="border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="category" class="text-sm font-medium text-gray-700">Category:</label>
                        <select name="category" id="category" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-full">
                            <option value="">All Categories</option>
                            <option value="IT & Software" {{ request('category') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                            <option value="Healthcare" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Business & Finance" {{ request('category') == 'Business & Finance' ? 'selected' : '' }}>Business & Finance</option>
                            <option value="Sales & Marketing" {{ request('category') == 'Sales & Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
                            <option value="Customer Service" {{ request('category') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                            <option value="Human Resources" {{ request('category') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                            <option value="Design & Creatives" {{ request('category') == 'Design & Creatives' ? 'selected' : '' }}>Design & Creatives</option>
                            <option value="Hospitality & Tourism" {{ request('category') == 'Hospitality & Tourism' ? 'selected' : '' }}>Hospitality & Tourism</option>
                            <option value="Construction" {{ request('category') == 'Construction' ? 'selected' : '' }}>Construction</option>
                            <option value="Manufacturing" {{ request('category') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="Transport & Logistics" {{ request('category') == 'Transport & Logistics' ? 'selected' : '' }}>Transport & Logistics</option>
                            <option value="Government" {{ request('category') == 'Government' ? 'selected' : '' }}>Government</option>
                            <option value="Science & Research" {{ request('category') == 'Science & Research' ? 'selected' : '' }}>Science & Research</option>
                            <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                        Filter
                    </button>
                </form>

                <a href="{{ route('admin.reports.download', request()->query()) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm whitespace-nowrap">
                    Download PDF
                </a>
            </div>
        </div>

        @if(!$errorMessage)
            <!-- KPI Cards Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-medium">New Applicants</h3>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalApplicants }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-medium">New Job Providers</h3>
                    <p class="text-4xl font-bold text-red-500 mt-2">{{ $totalProviders }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-medium">Applicants Hired</h3>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $totalHired }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-medium">Applicants Rejected</h3>
                    <p class="text-4xl font-bold text-orange-500 mt-2">{{ $totalRejected }}</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Daily Applicant Registrations</h2>
                    <div class="relative h-80"><canvas id="applicantRegisteredChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Daily Job Provider Registrations</h2>
                    <div class="relative h-80"><canvas id="jobProviderChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Daily Hired Applicants</h2>
                    <div class="relative h-80"><canvas id="hiredApplicantsChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Daily Rejected Applicants</h2>
                    <div class="relative h-80"><canvas id="rejectedApplicantsChart"></canvas></div>
                </div>
                <!-- MODIFICATION: Category Distribution Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md border lg:col-span-2">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Application Distribution by Category</h2>
                    <div class="relative h-96"><canvas id="categoryDistributionChart"></canvas></div>
                </div>
            </div>
        @else
            <!-- No Data Message -->
            <div class="bg-white p-6 rounded-lg shadow-md border max-w-4xl mx-auto text-center py-12">
                <h3 class="text-xl font-semibold text-gray-700">No Data Available</h3>
                <p class="text-gray-500 mt-2">{{ $errorMessage }}</p>
            </div>
        @endif
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

            function createChart(elementId, chartData, config) {
                const canvas = document.getElementById(elementId);
                if (!canvas || !chartData || chartData.values.reduce((a, b) => a + b, 0) === 0) {
                    if (canvas) {
                        const parent = canvas.parentElement;
                        parent.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No data to display for this chart.</div>';
                    }
                    return; 
                };

                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: (config.type === 'pie' || config.type === 'doughnut') ? 'right' : 'top',
                        }
                    }
                };

                if (config.type !== 'pie' && config.type !== 'doughnut') {
                    chartOptions.scales = {
                        y: {
                            beginAtZero: true,
                            ticks: {

                                precision: 0
                            }
                        }
                    };
                }

                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: config.type,
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: config.label,
                            data: chartData.values,
                            borderColor: config.borderColor,
                            backgroundColor: config.backgroundColor,
                            borderWidth: config.type === 'bar' ? 1 : 2,
                            fill: config.type === 'line',
                            tension: 0.2
                        }]
                    },
                    options: chartOptions
                });
            }

            // Initialize all charts
            createChart('applicantRegisteredChart', @json($applicantChartData), {
                type: 'line',
                label: 'New Applicants',
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)'
            });

            createChart('jobProviderChart', @json($jobProviderChartData), {
                type: 'line',
                label: 'New Job Providers',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)'
            });

            createChart('hiredApplicantsChart', @json($hiredChartData), {
                type: 'bar',
                label: 'Applicants Hired',
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            });

            createChart('rejectedApplicantsChart', @json($disapprovedChartData), {
                type: 'bar',
                label: 'Applicants Rejected',
                borderColor: 'rgba(255, 159, 64, 1)',
                backgroundColor: 'rgba(255, 159, 64, 0.6)'
            });

            // MODIFICATION: Create Category Distribution Chart
            createChart('categoryDistributionChart', @json($categoryChartData), {
                type: 'pie',
                label: 'Applications',
                // Chart.js provides default colors for pie charts which is great for dynamic data
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf', '#63ff84', '#eb36a2', '#56ffce']
            });
        });
    </script>
</body>
</html>