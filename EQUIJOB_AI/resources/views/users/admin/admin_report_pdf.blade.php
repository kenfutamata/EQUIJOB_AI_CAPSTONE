<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Administrator Report</title>
    <style>
        /* Using a more basic font stack for broad compatibility */
        body { font-family: DejaVu Sans, sans-serif; background-color: #ffffff; color: #374151; font-size: 12px; }
        .page-break { page-break-after: always; }
        h1 { font-size: 24px; font-weight: bold; margin: 0; }
        h2 { font-size: 18px; font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; }
        h3 { font-size: 14px; color: #4b5563; margin: 0 0 4px 0; }
        p { margin: 0; color: #6b7280; }
        .text-3xl { font-size: 28px; }
        .font-bold { font-weight: bold; }
        .mt-1 { margin-top: 0.25rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-8 { margin-bottom: 2rem; }
        .text-blue-600 { color: #2563eb; }
        .text-red-500 { color: #ef4444; }
        .text-green-600 { color: #16a34a; }
        .text-orange-500 { color: #f97316; }

        /* Table-based layouts for robust PDF rendering */
        .summary-table { width: 100%; border-spacing: 10px 0; margin: 0 -5px; }
        .summary-cell { width: 25%; display: table-cell; vertical-align: top; }
        .summary-box { background-color: #f9fafb; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; }

        .charts-table { width: 100%; border-spacing: 15px 0; page-break-inside: avoid; }
        .chart-cell { width: 50%; display: table-cell; padding: 0 5px; }
        .chart-img { width: 100%; }
        
        .category-chart-container { text-align: center; margin-top: 1rem; padding: 1rem; border: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 8px; }
        .category-chart-img { max-width: 80%; display: inline-block; }
    </style>
</head>
<body>

    <header class="mb-8">
        <h1>Administrator Report</h1>
        <p>System-wide statistics for {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
        @if(request('category'))
        <p>Filtered by Category: <strong>{{ request('category') }}</strong></p>
        @endif
    </header>

    <main>
        <!-- KPI Cards Section -->
        <h2>Monthly Summary</h2>
        <table class="summary-table">
            <tr>
                <td class="summary-cell">
                    <div class="summary-box">
                        <h3>New Applicants</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalApplicants }}</p>
                    </div>
                </td>
                <td class="summary-cell">
                    <div class="summary-box">
                        <h3>New Job Providers</h3>
                        <p class="text-3xl font-bold text-red-500 mt-1">{{ $totalProviders }}</p>
                    </div>
                </td>
                <td class="summary-cell">
                    <div class="summary-box">
                        <h3>Applicants Hired</h3>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $totalHired }}</p>
                    </div>
                </td>
                <td class="summary-cell">
                    <div class="summary-box">
                        <h3>Applicants Rejected</h3>
                        <p class="text-3xl font-bold text-orange-500 mt-1">{{ $totalRejected }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Daily Trends Section -->
        <h2>Daily Trends</h2>
        <table class="charts-table">
            <tr>
                @if($applicantChartImageUrl)
                <td class="chart-cell"><img src="{{ $applicantChartImageUrl }}" alt="Applicant Registrations Chart" class="chart-img"></td>
                @endif
                @if($jobProviderChartImageUrl)
                <td class="chart-cell"><img src="{{ $jobProviderChartImageUrl }}" alt="Job Provider Registrations Chart" class="chart-img"></td>
                @endif
            </tr>
             <tr>
                 @if($hiredChartImageUrl)
                <td class="chart-cell"><img src="{{ $hiredChartImageUrl }}" alt="Hired Applicants Chart" class="chart-img"></td>
                @endif
                @if($disapprovedChartImageUrl)
                <td class="chart-cell"><img src="{{ $disapprovedChartImageUrl }}" alt="Rejected Applicants Chart" class="chart-img"></td>
                @endif
            </tr>
        </table>
        
        <div class="page-break"></div>

        <!-- Category Distribution Section -->
        <h2>Category Summary</h2>
        @if(isset($categoryChartImageUrl) && $categoryChartImageUrl)
            <div class="category-chart-container">
                <img src="{{ $categoryChartImageUrl }}" alt="Application Distribution by Category Chart" class="category-chart-img">
            </div>
        @else
            <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 8px; border: 1px solid #e5e7eb; text-align: center; margin-top: 1rem;">
                <p>No application data available to show category distribution.</p>
            </div>
        @endif
    </main>

</body>
</html>