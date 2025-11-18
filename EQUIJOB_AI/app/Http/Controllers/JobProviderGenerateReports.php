<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuickChart\QuickChart;

class JobProviderGenerateReports extends Controller
{
    /**
     * Display the report generation page with interactive JS charts.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('job_provider')->user();
        $ratingsChartData = null;
        $trendsChartData = null;
        $errorMessage = null;

        try {
            $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
            $selectedCategory = $request->input('category');
            $date = Carbon::parse($selectedMonth . '-01');

            // --- RATINGS QUERY ---
            $ratingCounts = Feedbacks::query()
                ->select('feedbacks.rating', DB::raw('COUNT(*) as count'))
                ->join(DB::raw('"jobPosting"'), 'feedbacks.jobPostingID', '=', DB::raw('"jobPosting".id'))
                ->where(DB::raw('"jobPosting"."jobProviderID"'), $user->id)
                ->whereYear(DB::raw('"jobPosting"."created_at"'), $date->year)
                ->whereMonth(DB::raw('"jobPosting"."created_at"'), $date->month)
                ->when($selectedCategory, function ($query) use ($selectedCategory) {
                    return $query->where(DB::raw('"jobPosting"."category"'), $selectedCategory);
                })
                ->whereNotNull('feedbacks.rating')
                ->groupBy('feedbacks.rating')
                ->pluck('count', 'rating');

            if (!$ratingCounts->isEmpty()) {
                $ratingsChartData = [
                    'labels' => [1, 2, 3, 4, 5],
                    'values' => [
                        $ratingCounts->get(1, 0),
                        $ratingCounts->get(2, 0),
                        $ratingCounts->get(3, 0),
                        $ratingCounts->get(4, 0),
                        $ratingCounts->get(5, 0)
                    ],
                ];
            }

            $applicationTrends = JobApplication::query()
                ->select(DB::raw('"jobApplications".status'), DB::raw('COUNT(*) as count'))
                ->join(DB::raw('"jobPosting"'), DB::raw('"jobApplications"."jobPostingID"'), '=', DB::raw('"jobPosting".id'))
                ->where(DB::raw('"jobPosting"."jobProviderID"'), $user->id)
                ->whereYear(DB::raw('"jobPosting"."created_at"'), $date->year)
                ->whereMonth(DB::raw('"jobPosting"."created_at"'), $date->month)
                ->whereIn(DB::raw('"jobApplications".status'), ['Hired', 'Rejected', 'Pending', 'For Interview', 'Withdrawn'])
                ->when($selectedCategory, function ($query) use ($selectedCategory) {
                    return $query->where(DB::raw('"jobPosting"."category"'), $selectedCategory);
                })
                ->groupBy(DB::raw('"jobApplications".status'))
                ->pluck('count', 'status');


            if ($applicationTrends->sum() > 0) {
                $trendsChartData = [
                    'hired'        => $applicationTrends->get('Hired', 0),
                    'rejected'     => $applicationTrends->get('Rejected', 0),
                    'pending'      => $applicationTrends->get('Pending', 0),
                    'forInterview' => $applicationTrends->get('For Interview', 0),
                    'withdrawn'    => $applicationTrends->get('Withdrawn', 0),
                ];
            }
            if ($ratingsChartData === null && $trendsChartData === null) {
                $errorMessage = "No data available for " . $date->format('F Y');
                if ($selectedCategory) {
                    $errorMessage .= " in the '" . e($selectedCategory) . "' category";
                }
                $errorMessage .= ".";
            }
        } catch (\Exception $e) {
            Log::error('Chart data generation failed: ' . $e->getMessage());
            $errorMessage = 'The server encountered an error while processing your report data.';
        }

        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();

        return response()->view('users.job-provider.job_provider_generate_report', compact(
            'user',
            'ratingsChartData',
            'trendsChartData',
            'errorMessage',
            'selectedMonth',
            'notifications',
            'unreadNotifications'
        ))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    /**
     * Gathers report data and generates chart images for the PDF.
     */
    private function getReportData(Request $request): array
    {
        $user = Auth::guard('job_provider')->user();

        $ratingsChartData = null;
        $trendsChartData = null;
        $errorMessage = null;
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Variables to hold the generated chart image URLs
        $ratingsChartImageUrl = null;
        $trendsChartImageUrl = null;

        try {
            $selectedCategory = $request->input('category');
            $date = Carbon::parse($selectedMonth . '-01');

            // --- FIX: RESTORED THE FULL, WORKING RATINGS QUERY ---
            $ratingCounts = Feedbacks::query()
                ->select('feedbacks.rating', DB::raw('COUNT(*) as count'))
                ->join(DB::raw('"jobPosting"'), 'feedbacks.jobPostingID', '=', DB::raw('"jobPosting".id'))
                ->where(DB::raw('"jobPosting"."jobProviderID"'), $user->id)
                ->whereYear(DB::raw('"jobPosting"."created_at"'), $date->year)
                ->whereMonth(DB::raw('"jobPosting"."created_at"'), $date->month)
                ->when($selectedCategory, function ($query) use ($selectedCategory) {
                    return $query->where(DB::raw('"jobPosting"."category"'), $selectedCategory);
                })
                ->whereNotNull('feedbacks.rating')
                ->groupBy('feedbacks.rating')
                ->pluck('count', 'rating');


            if (!$ratingCounts->isEmpty()) {
                $ratingsChartData = [
                    'values' => [
                        $ratingCounts->get(1, 0),
                        $ratingCounts->get(2, 0),
                        $ratingCounts->get(3, 0),
                        $ratingCounts->get(4, 0),
                        $ratingCounts->get(5, 0)
                    ],
                ];

                // --- GENERATE RATINGS CHART IMAGE FOR PDF ---
                $ratingsChartImageUrl = null;
                if ($ratingsChartData) {
                    $ratingsChartImageUrl = $this->makeQuickChartUrl([
                        'type' => 'bar',
                        'data' => [
                            'labels' => ['★ 1', '★ 2', '★ 3', '★ 4', '★ 5'],
                            'datasets' => [[
                                'label' => '# of Reviews',
                                'data' => $ratingsChartData['values']
                            ]]
                        ],
                        'options' => [
                            'legend' => ['display' => false],
                            'scales' => [
                                'yAxes' => [[
                                    'ticks' => ['beginAtZero' => true, 'stepSize' => 1]
                                ]]
                            ]
                        ]
                    ]);
                }
            }

            $applicationTrends = JobApplication::query()
                ->select(DB::raw('"jobApplications".status'), DB::raw('COUNT(*) as count'))
                ->join(DB::raw('"jobPosting"'), DB::raw('"jobApplications"."jobPostingID"'), '=', DB::raw('"jobPosting".id'))
                ->where(DB::raw('"jobPosting"."jobProviderID"'), $user->id)
                ->whereYear(DB::raw('"jobPosting"."created_at"'), $date->year)
                ->whereMonth(DB::raw('"jobPosting"."created_at"'), $date->month)
                ->whereIn(DB::raw('"jobApplications".status'), ['Hired', 'Rejected', 'Pending', 'For Interview', 'Withdrawn'])
                ->when($selectedCategory, function ($query) use ($selectedCategory) {
                    return $query->where(DB::raw('"jobPosting"."category"'), $selectedCategory);
                })
                ->groupBy(DB::raw('"jobApplications".status'))
                ->pluck('count', 'status');


            if ($applicationTrends->sum() > 0) {
                $trendsChartData = [
                    'hired' => $applicationTrends->get('Hired', 0),
                    'rejected' => $applicationTrends->get('Rejected', 0),
                    'pending' => $applicationTrends->get('Pending', 0),
                    'forInterview' => $applicationTrends->get('For Interview', 0),
                    'withdrawn' => $applicationTrends->get('Withdrawn', 0),
                ];

                // --- GENERATE TRENDS CHART IMAGE FOR PDF ---
                $trendsChartImageUrl = null;
                if ($trendsChartData) {
                    $trendsChartImageUrl = $this->makeQuickChartUrl([
                        'type' => 'doughnut',
                        'data' => [
                            'labels' => ['Hired', 'Rejected', 'For Interview', 'Pending', 'Withdrawn'],
                            'datasets' => [[
                                'data' => [
                                    $trendsChartData['hired'],
                                    $trendsChartData['rejected'],
                                    $trendsChartData['forInterview'],
                                    $trendsChartData['pending'],
                                    $trendsChartData['withdrawn'],
                                ]
                            ]]
                        ]
                    ]);
                }
            }

            if ($ratingsChartData === null && $trendsChartData === null) {
                $errorMessage = "No data available for " . $date->format('F Y');
                if ($selectedCategory) {
                    $errorMessage .= " in the '" . e($selectedCategory) . "' category";
                }
                $errorMessage .= ".";
            }
        } catch (\Exception $e) {
            Log::error('Chart data generation failed: ' . $e->getMessage());
            $errorMessage = 'The server encountered an error while processing your report data.';
        }

        return [
            'ratingsChartData' => $ratingsChartData,
            'trendsChartData' => $trendsChartData,
            'errorMessage' => $errorMessage,
            'selectedMonth' => $selectedMonth,
            'ratingsChartImageUrl' => $ratingsChartImageUrl,
            'trendsChartImageUrl' => $trendsChartImageUrl,
        ];
    }
    private function makeQuickChartUrl(array $config)
    {
        return 'https://quickchart.io/chart?c=' . urlencode(json_encode($config));
    }
    /**
     * Generate and download the report as a PDF.
     */
    public function download(Request $request)
    {
        $user = Auth::guard('job_provider')->user();
        $data = $this->getReportData($request);
        $date = Carbon::parse($data['selectedMonth']);
        $jobApplicationCount = JobApplication::whereHas('jobPosting', function ($query) use ($user) {
            $query->where('jobProviderID', $user->id);
        })->count();
        $jobApplicantInterviewCount = JobApplication::whereHas('jobPosting', function ($query) use ($user) {
            $query->where('jobProviderID', $user->id);
        })->where('status', 'Interview')->count();
        $jobApplicantHiredCount = JobApplication::whereHas('jobPosting', function ($q) use ($user) {
            $q->where('jobProviderID', $user->id);
        })->where('status', 'Hired')
            ->count();
        $jobApplicantRejectedCount = JobApplication::whereHas('jobPosting', function ($q) use ($user) {
            $q->where('jobProviderID', $user->id);
        })->where('status', 'Rejected')
            ->count();
        $jobPostingCount = JobPosting::where('jobProviderID', $user->id)->count();

        $fileName = 'Report-' . e($user->companyName) . '-' . $date->format('F-Y') . '.pdf';
        $pdf = Pdf::loadView('users.job-provider.job_provider_report_pdf', array_merge($data, ['user' => $user, 'jobApplicationCount' => $jobApplicationCount, 'jobApplicantInterviewCount' => $jobApplicantInterviewCount, 'jobApplicantHiredCount' => $jobApplicantHiredCount, 'jobPostingCount' => $jobPostingCount, 'jobApplicantRejectedCount'=> $jobApplicantRejectedCount]));
        return $pdf->download($fileName);
    }
}
