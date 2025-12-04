<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\JobPosting;
use App\Models\Province;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function ViewLandingPage()
    {
        return view('landing_page');
    }

    public function ViewSignInPage()
    {
        return view('sign-in-page.sign_in');
    }

    public function ViewAboutUsPage()
    {
        return view('about_us');
    }
    public function ViewContactUsPage()
    {
        return view('contact_us');
    }

    public function ViewJobsPage(Request $request)
    {
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

        // *** THIS IS THE CORRECTED LOGIC ***
        if ($provinceId) {
            // 1. Find the Province model using the ID from the filter.
            $province = Province::find($provinceId);
            // 2. If the province is found, use its NAME to query the jobPosting table.
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
        $response = response()->view('jobs', [
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
