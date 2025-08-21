<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminGenerateReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $applicantChartData = null;
        $jobProviderChartData = null;
        $hiredChartData = null;
        $disapprovedChartData = null; 
        $errorMessage = null;

        try {
            $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
            $date = Carbon::parse($selectedMonth . '-01');
            $period = CarbonPeriod::create($date->copy()->startOfMonth(), $date->copy()->endOfMonth());
            $chartLabels = [];
            foreach ($period as $day) {
                $chartLabels[] = $day->format('M d');
            }
            //Query 1: Registered Applicants Report
            $applicantRegistrations = User::query()
                ->select(DB::raw('DATE(created_at) as registration_date'), DB::raw('COUNT(*) as count'))
                ->where('role', 'Applicant')
                ->where('status', 'Active')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->pluck('count', 'registration_date');

            $applicantDataPoints = [];
            foreach ($period as $day) {
                $applicantDataPoints[] = $applicantRegistrations->get($day->format('Y-m-d'), 0);
            }

            if (array_sum($applicantDataPoints) > 0) {
                $applicantChartData = [
                    'labels' => $chartLabels,
                    'values' => $applicantDataPoints,
                ];
            }
            //Query 2: Job Provider Registered
            $jobProviderRegistrations = User::query()
                ->select(DB::raw('DATE(created_at) as registration_date'), DB::raw('COUNT(*) as count'))
                ->where('role', 'Job Provider')
                ->where('status', 'Active')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->pluck('count', 'registration_date');

            $jobProviderDataPoints = [];
            foreach ($period as $day) {
                $jobProviderDataPoints[] = $jobProviderRegistrations->get($day->format('Y-m-d'), 0);
            }

            if (array_sum($jobProviderDataPoints) > 0) {
                $jobProviderChartData = [
                    'labels' => $chartLabels,
                    'values' => $jobProviderDataPoints,
                ];
            }
            // Query 3: Hired Applicants
            $hiredApplications = JobApplication::query()
                ->select(DB::raw('DATE(updated_at) as hired_date'), DB::raw('COUNT(*) as count'))
                ->where('status', 'Hired')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->groupBy(DB::raw('DATE(updated_at)'))
                ->orderBy(DB::raw('DATE(updated_at)'))
                ->pluck('count', 'hired_date');

            $hiredDataPoints = [];
            foreach ($period as $day) {
                $hiredDataPoints[] = $hiredApplications->get($day->format('Y-m-d'), 0);
            }

            if (array_sum($hiredDataPoints) > 0) {
                $hiredChartData = [
                    'labels' => $chartLabels,
                    'values' => $hiredDataPoints,
                ];
            }

            //Query 4: Disapproved Applications 
            $disapprovedApplications = JobApplication::query()
            ->select(DB::raw('DATE(updated_at) as rejected_date'), DB::raw('COUNT(*) as count'))
            ->where('status', 'Rejected')
            ->whereYear('updated_at', $date->year)
            ->whereMonth('updated_at', $date->month)
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy(DB::raw('DATE(updated_at)'))
            ->pluck('count', 'rejected_date');

            $disapprovedDataPoints = []; 
            foreach($period as $day){
                $disapprovedDataPoints[] = $disapprovedApplications->get($day->format('Y-m-d'), 0); 
            }

            if(array_sum($disapprovedDataPoints) > 0){
                $disapprovedChartData = [
                    'labels' => $chartLabels, 
                    'values'=>$disapprovedDataPoints,
                ];
            }
            if ($applicantChartData === null && $jobProviderChartData === null && $hiredChartData === null && $disapprovedChartData === null) {
                $errorMessage = 'No data available for ' . $date->format('F Y') . '.';
            }
        } catch (Exception $e) {
            Log::error('Admin chart generation failed', ['exception' => $e]);
            $errorMessage = 'The server encountered an error while processing the report data.';
        }

        $notifications = $user?->notifications ?? collect();
        $unreadNotifications = $user?->unreadNotifications ?? collect();
        return response()->view('users.admin.admin_generate_reports', compact(
            'user',
            'applicantChartData',
            'jobProviderChartData',
            'hiredChartData',
            'disapprovedChartData', 
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
