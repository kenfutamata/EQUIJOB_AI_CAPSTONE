<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use App\Models\JobApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobProviderGenerateReports extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('job_provider')->user();
        $ratingsChartData = null;
        $trendsChartData = null;
        $errorMessage = null;

        try {
            $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
            $date = Carbon::parse($selectedMonth . '-01');

            $ratingCounts = Feedbacks::query()
                ->select('feedbacks.rating', DB::raw('COUNT(*) as count'))
                ->join(DB::raw('"jobPosting"'), 'feedbacks.jobPostingID', '=', DB::raw('"jobPosting".id'))
                ->where(DB::raw('"jobPosting"."jobProviderID"'), $user->id)
                ->whereNotNull('feedbacks.rating')
                ->whereYear('feedbacks.created_at', $date->year)
                ->whereMonth('feedbacks.created_at', $date->month)
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
                ->whereYear(DB::raw('"jobApplications".updated_at'), $date->year)
                ->whereMonth(DB::raw('"jobApplications".updated_at'), $date->month)
                ->whereIn(DB::raw('"jobApplications".status'), ['Hired', 'Rejected', 'Pending', 'For Interview', 'Withdrawn'])
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
                $errorMessage = "No data available for " . $date->format('F Y') . ".";
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
}
