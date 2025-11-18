<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminGenerateReportsController extends Controller
{
    /**
     * Display the interactive report page.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $reportData = $this->getReportData($request);
        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();
        return response()->view('users.admin.admin_generate_reports', array_merge(
            [
                'user' => $user,
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications
            ],
            $reportData
        ));
    }

    /**
     * Generate and download the report as a PDF.
     */
    public function download(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $reportData = $this->getReportData($request, true); // Pass true to generate image URLs

        if (!empty($reportData['errorMessage'])) {
            return redirect()->route('admin.reports.index')->with('error', $reportData['errorMessage']);
        }

        $date = Carbon::parse($reportData['selectedMonth']);
        $fileName = 'Admin-Report-' . $date->format('F-Y') . '.pdf';
        $pdf = Pdf::loadView('users.admin.admin_report_pdf', array_merge(['user' => $user], $reportData));
        return $pdf->download($fileName);
    }

    /**
     * Gathers all necessary data for the reports.
     */
    private function getReportData(Request $request, bool $forPdf = false): array
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $data = [
            'selectedMonth' => $selectedMonth, 'errorMessage' => null, 'totalApplicants' => 0, 'totalProviders' => 0, 'totalHired' => 0, 'totalRejected' => 0, 'applicantChartData' => null, 'jobProviderChartData' => null, 'hiredChartData' => null, 'disapprovedChartData' => null, 'categoryChartData' => null, // Consistent variable name
        ];

        try {
            $selectedCategory = $request->input('category'); // Read category for PDF filtering
            $date = Carbon::parse($selectedMonth . '-01');
            $period = CarbonPeriod::create($date->copy()->startOfMonth(), $date->copy()->endOfMonth());

            $applicantsQuery = User::query()->where('role', 'Applicant')->where('status', 'Active')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
            $providersQuery = User::query()->where('role', 'Job Provider')->where('status', 'Active')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month);
            $hiredQuery = JobApplication::query()->where('status', 'Hired')->whereYear('updated_at', $date->year)->whereMonth('updated_at', $date->month);
            $rejectedQuery = JobApplication::query()->where('status', 'Rejected')->whereYear('updated_at', $date->year)->whereMonth('updated_at', $date->month);

            // Re-add category filter logic for PDF and potential URL filtering
            if ($selectedCategory) {
                $hiredQuery->whereHas('jobPosting', function (Builder $query) use ($selectedCategory) {
                    $query->where('category', $selectedCategory);
                });
                $rejectedQuery->whereHas('jobPosting', function (Builder $query) use ($selectedCategory) {
                    $query->where('category', $selectedCategory);
                });
            }

            $data['totalApplicants'] = $applicantsQuery->count();
            $data['totalProviders'] = $providersQuery->count();
            $data['totalHired'] = $hiredQuery->count();
            $data['totalRejected'] = $rejectedQuery->count();

            $data['applicantChartData'] = $this->generateDailyTrendData($applicantsQuery->clone(), $period, 'created_at');
            $data['jobProviderChartData'] = $this->generateDailyTrendData($providersQuery->clone(), $period, 'created_at');
            $data['hiredChartData'] = $this->generateDailyTrendData($hiredQuery->clone(), $period, 'updated_at');
            $data['disapprovedChartData'] = $this->generateDailyTrendData($rejectedQuery->clone(), $period, 'updated_at');

            $categoryDistribution = DB::table('jobApplications')
                ->join('jobPosting', 'jobApplications.jobPostingID', '=', 'jobPosting.id')
                ->whereYear('jobApplications.created_at', $date->year)
                ->whereMonth('jobApplications.created_at', $date->month)
                ->select('jobPosting.category', DB::raw('COUNT(*) as count'))
                ->groupBy('jobPosting.category')
                ->orderBy('count', 'desc')
                ->pluck('count', 'jobPosting.category');
            
            $data['categoryChartData'] = $categoryDistribution->isNotEmpty() ? [
                'labels' => $categoryDistribution->keys()->toArray(), 'values' => $categoryDistribution->values()->toArray(),
            ] : null;

            if ($forPdf) {
                $data['applicantChartImageUrl'] = $this->generateChartUrl($data['applicantChartData'], ['type' => 'line', 'label' => 'New Applicants', 'title' => 'Daily Applicant Registrations', 'borderColor' => '#36a2eb', 'backgroundColor' => 'rgba(54, 162, 235, 0.2)']);
                $data['jobProviderChartImageUrl'] = $this->generateChartUrl($data['jobProviderChartData'], ['type' => 'line', 'label' => 'New Job Providers', 'title' => 'Daily Job Provider Registrations', 'borderColor' => '#ff6384', 'backgroundColor' => 'rgba(255, 99, 132, 0.2)']);
                $data['hiredChartImageUrl'] = $this->generateChartUrl($data['hiredChartData'], ['type' => 'bar', 'label' => 'Hired Applicants', 'title' => 'Daily Hired Applicants', 'borderColor' => '#4bc0c0', 'backgroundColor' => 'rgba(75, 192, 192, 0.6)']);
                $data['disapprovedChartImageUrl'] = $this->generateChartUrl($data['disapprovedChartData'], ['type' => 'bar', 'label' => 'Rejected Applicants', 'title' => 'Daily Rejected Applicants', 'borderColor' => '#ff9f40', 'backgroundColor' => 'rgba(255, 159, 64, 0.6)']);
                // Ensure the category chart URL is generated for the PDF
                $data['categoryChartImageUrl'] = $this->generateChartUrl($data['categoryChartData'], ['type' => 'pie', 'label' => 'Applications', 'title' => 'Application Distribution by Category']);
            }

            if (empty(array_filter(array_slice($data, 2, 4)))) { // Checks if all KPIs are zero
                $data['errorMessage'] = 'No data available for ' . $date->format('F Y') . '.';
            }
        } catch (Exception $e) {
            Log::error('Admin report generation failed', ['exception' => $e]);
            $data['errorMessage'] = 'The server encountered an error while processing the report data.';
        }
        return $data;
    }
    /**
     * Generates daily data points for a given query and time period.
     */
    private function generateDailyTrendData(Builder $query, CarbonPeriod $period, string $dateColumn): ?array
    {
        $dailyCounts = $query
            ->select(DB::raw("DATE($dateColumn) as date"), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw("DATE($dateColumn)"))
            ->orderBy(DB::raw("DATE($dateColumn)"))
            ->pluck('count', 'date');

        $dataPoints = [];
        $chartLabels = [];
        foreach ($period as $day) {
            $dataPoints[] = $dailyCounts->get($day->format('Y-m-d'), 0);
            $chartLabels[] = $day->format('M d');
        }

        if (array_sum($dataPoints) > 0) {
            return [
                'labels' => $chartLabels,
                'values' => $dataPoints,
            ];
        }

        return null; // Return null if there's no data.
    }

    /**
     * Generates a URL for a static chart image using QuickChart.io.
     */
    private function generateChartUrl(?array $chartData, array $config): ?string
    {
        if (!$chartData || array_sum($chartData['values']) === 0) {
            return null;
        }

        $chartConfig = [
            'type' => $config['type'],
            'data' => [
                'labels' => $chartData['labels'],
                'datasets' => [[
                    'label' => $config['label'] ?? 'Dataset',
                    'data' => $chartData['values'],
                    'borderColor' => $config['borderColor'] ?? '#ffffff',
                    'backgroundColor' => $config['backgroundColor'] ?? ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf', '#63ff84', '#eb36a2', '#56ffce'],
                    'fill' => $config['type'] === 'line'
                ]]
            ],
            'options' => [
                'title' => ['display' => true, 'text' => $config['title'] ?? 'Chart', 'fontSize' => 16, 'padding' => 20],
                'legend' => ['position' => ($config['type'] === 'pie' || $config['type'] === 'doughnut') ? 'right' : 'top'],
                'scales' => ($config['type'] !== 'pie' && $config['type'] !== 'doughnut')
                    ? ['yAxes' => [['ticks' => ['beginAtZero' => true, 'stepSize' => 1]]]]
                    : null
            ]
        ];

        return 'https://quickchart.io/chart?width=500&height=300&c=' . urlencode(json_encode($chartConfig));
    }
}
