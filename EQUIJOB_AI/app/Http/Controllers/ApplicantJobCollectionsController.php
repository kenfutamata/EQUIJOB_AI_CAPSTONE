<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Province;
use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantJobCollectionsController extends Controller
{
    /**
     * Fetch cities for a given province and return as JSON for AJAX requests.
     */
    public function getCities(Province $province)
    {
        $cities = $province->cities()->orderBy('cityName', 'asc')->get(['id', 'cityName']);
        return response()->json($cities);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('applicant')->user();

        $search = $request->input('search');
        $provinceId = $request->input('province');
        $cityId = $request->input('city'); 
        $category = $request->input('category');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $provinces = Province::orderBy('provinceName', 'asc')->get();
        $cities = collect();
        if ($provinceId) {
            $selectedProvince = Province::find($provinceId);
            if ($selectedProvince) {
                $cities = $selectedProvince->cities()->orderBy('cityName', 'asc')->get();
            }
        }
        $query = JobPosting::withCount(['jobApplications',
            'jobApplications as interviews_count' => function ($q) {
                $q->where('status', 'For Interview');
            },
        ])->where('status', 'For Posting');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                    ->orWhere('companyName', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            });
        }
        
        if ($category) {
            $query->where('category', $category);
        }

        if ($provinceId) {
            $province = Province::find($provinceId);
            if ($province) {
                $query->where('provinceName', $province->provinceName);
            }
        }

        if ($cityId) {
            $city = Cities::find($cityId);
            if ($city) {
                $query->where('cityName', $city->cityName);
            }
        }

        if ($fromDate) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $sort = $request->input('sort', 'updated_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $collections = $query->paginate(12)->withQueryString();

        $response = response()->view('users.applicant.applicant_job_collections', [
            'user' => $user,
            'notifications' => $user->notifications,
            'unreadNotifications' => $user->unreadNotifications,
            'collections' => $collections,
            'provinces' => $provinces,
            'cities' => $cities,
        ]);

        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        
        return $response;
    }
}