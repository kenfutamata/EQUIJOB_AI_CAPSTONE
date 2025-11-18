<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EQUIJOB - Job Provider Generate Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/photos/landing_page/equijob_logo.png')}}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 w-[234px] h-full z-40 bg-[#c3d2f7]">
        <x-job-provider-sidebar />
    </aside>

    <!-- Topbar -->
    <header class="fixed top-0 left-[234px] right-0 h-16 z-30 bg-white border-b border-gray-200 shadow-sm">
        <x-topbar :user="$user" :notifications="$notifications" :unreadNotifications="$unreadNotifications" />
    </header>

    <!-- Main Content -->
    <main class="ml-[234px] mt-[64px] p-6 min-h-screen">

        @foreach (['Success' => 'green', 'error' => 'red', 'Delete_Success' => 'green'] as $key => $color)
        @if (session($key))
        <div id="notification-bar" class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-{{ $color }}-500 text-white px-6 py-3 rounded shadow-lg z-50 transition-opacity duration-500">
            {{ session($key) }}
        </div>
        @endif
        @endforeach

        <div class="container mx-auto p-4 sm:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                <h1 class="text-3xl font-bold text-gray-800">Job Provider Report for: {{$user->companyName}}</h1>

                <a href="{{route('job-provider-generate-report-download-pdf')}}" class="hidden sm:block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                    Download as PDF
                </a>
                <form method="GET" action="{{ url()->current() }}" class="w-full md:w-auto bg-white p-3 rounded-lg shadow-sm border border-gray-200 flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label for="month" class="text-sm font-medium text-gray-700 whitespace-nowrap">Select Month:</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-full">
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label for="category" class="text-sm font-medium text-gray-700">Category:</label>
                        <select name="category" id="category" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-full">
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
                    <button type="submit" class="hidden sm:block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                        Filter
                    </button>
                </form>
            </div>

            @if($ratingsChartData || $trendsChartData)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Feedback Ratings for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($ratingsChartData)
                    <div class="mx-auto relative h-80 max-w-lg"><canvas id="ratingsChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No rating data for this period.</p>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">
                        Application Outcomes for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}
                    </h2>
                    @if($trendsChartData)
                    <div class="mx-auto relative h-80 max-w-lg"><canvas id="trendsChart"></canvas></div>
                    @else
                    <p class="text-center py-10 text-gray-500">No application data for this period.</p>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white p-6 rounded-lg shadow-md border max-w-4xl mx-auto text-center py-12">
                <h3 class="text-xl font-semibold text-gray-700">No Data Available</h3>
                <p class="text-gray-500 mt-2">{{ $errorMessage ?? 'No data found for the selected month and category. Please adjust your filters.' }}</p>
            </div>
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notifBar = document.getElementById('notification-bar');
            if (notifBar) {
                setTimeout(() => {
                    notifBar.style.opacity = '0';
                    setTimeout(() => notifBar.remove(), 500);
                }, 3000);
            }

            @if($ratingsChartData)
            const ratingsData = @json($ratingsChartData);
            new Chart(document.getElementById('ratingsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ratingsData.labels.map(label => `★ ${label}`),
                    datasets: [{
                        label: '# of Reviews',
                        data: ratingsData.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            @endif

            @if($trendsChartData)
            const trendsData = {{ Illuminate\Support\Js::from($trendsChartData) }};

            new Chart(document.getElementById('trendsChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Hired', 'Rejected', 'For Interview', 'Pending', 'Withdrawn'],
                    datasets: [{
                        data: [
                            trendsData.hired,
                            trendsData.rejected,
                            trendsData.forInterview,
                            trendsData.pending,
                            trendsData.withdrawn
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            @endif
        });
    </script>
</body>

</html>