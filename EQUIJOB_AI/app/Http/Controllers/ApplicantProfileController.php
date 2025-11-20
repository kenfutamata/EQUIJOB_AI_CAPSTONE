<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Province;
use App\Models\User;
use App\Models\users;
use App\Services\GeminiService; // 1. IMPORT THE SERVICE
use App\Services\SupabaseStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicantProfileController extends Controller
{
    // 2. CREATE A PROPERTY TO HOLD THE SERVICE
    protected $geminiService;

    // 3. INJECT THE SERVICE IN THE CONSTRUCTOR
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        $authenticatedUser = Auth::guard('applicant')->user();
        $user = users::with(['province', 'city'])->findOrFail($authenticatedUser->id);
        $provinces = Province::all(); 
        $cities = []; 

        $selectedProvinceId = old('provinceId', $user->provinceId);
        if($selectedProvinceId){
            $cities = Cities::where('provinceId', $selectedProvinceId)->get();
        }
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;
        $provinces = Province::all();

        $response = response()->view('users.applicant.applicant_profile', compact('user', 'notifications', 'unreadNotifications', 'provinces', 'cities'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }

    public function update(Request $request, SupabaseStorageService $supabase)
    {
        $user = Auth::guard('applicant')->user();
        $validatedData = $request->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phoneNumber' => 'nullable|string|max:11',
            'dateOfBirth' => 'nullable|date|before_or_equal:today',
            'address' => 'nullable|string|max:255',
            'provinceId' => 'nullable|exists:provinces,id',
            'cityId' => 'nullable|exists:cities,id',
            'typeOfDisability' => 'nullable|string|max:255',
            'pwdId' => 'nullable|string|max:19|regex:/^\d{2}-\d{4}-\d{3}-\d{7}$/',
            'upload_pwd_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'profilePicture' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|mimes:jpg,jpeg,png|max:4096',
            'certificates_to_delete' => 'nullable|array',
            'certificates_to_delete.*' => 'string|url',
        ]);

        try {
            // --- Certificate Deletion ---
            $currentCertUrls = $user->certificates ?? [];
            $currentExtractedCerts = $user->extractedCertificates ?? [];
            $certificatesToDelete = $request->input('certificates_to_delete', []);

            $survivingCertUrls = array_diff($currentCertUrls, $certificatesToDelete);
            $survivingExtractedCerts = array_filter($currentExtractedCerts, function ($cert) use ($certificatesToDelete) {
                return !in_array($cert['url'] ?? null, $certificatesToDelete);
            });

            foreach ($certificatesToDelete as $urlToDelete) {
                $pathToDelete = Str::after($urlToDelete, 'equijob_storage/');
                $supabase->delete($pathToDelete);
            }

            // --- New Certificate Upload & AI Extraction ---
            $newCertificateUrls = [];
            $newlyExtractedData = [];
            $failedFiles = [];

            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $file) {
                    $extractedData = $this->extractCertificateData($file);

                    // ** THE FIX: Stricter validation. Check for all three fields. **
                    $isDataValid = $extractedData
                        && !empty($extractedData['skill_name'])
                        && !empty($extractedData['issuer'])
                        && !empty($extractedData['issue_date']);

                    if ($isDataValid) {
                        $url = $supabase->upload($file, 'certificates');

                        if ($url) {
                            $newCertificateUrls[] = $url;
                            $extractedData['url'] = $url;
                            $newlyExtractedData[] = $extractedData;
                        } else {
                            $failedFiles[] = $file->getClientOriginalName();
                            Log::error('Supabase upload failed for a valid certificate: ' . $file->getClientOriginalName());
                        }
                    } else {
                        // AI failed to find all required fields. Reject the file.
                        $failedFiles[] = $file->getClientOriginalName();
                        Log::warning('GeminiService did not find all required certificate fields, skipping upload for: ' . $file->getClientOriginalName(), ['extracted_data' => $extractedData]);
                    }
                }
            }

            // --- Update User Model ---
            if ($request->hasFile('upload_pwd_card')) {
                $validatedData['upload_pwd_card'] = $supabase->upload($request->file('upload_pwd_card'), 'upload_pwd_card');
            }
            if ($request->hasFile('profilePicture')) {
                $validatedData['profilePicture'] = $supabase->upload($request->file('profilePicture'), 'profilePicture');
            }

            unset($validatedData['certificates'], $validatedData['certificates_to_delete']);

            $user->update($validatedData);

            $user->certificates = array_merge(array_values($survivingCertUrls), $newCertificateUrls);
            $user->extractedCertificates = array_merge(array_values($survivingExtractedCerts), $newlyExtractedData);
            $user->save();

            // --- Construct Dynamic Success/Warning Message ---
            $successMessage = 'Profile and skills updated successfully.';
            if (!empty($failedFiles)) {
                $successMessage .= ' However, the AI could not validate the following files as certificates (they were not uploaded): ' . implode(', ', $failedFiles) . '.';
            }

            return redirect()->back()->with('Success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Profile Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the profile.');
        }
    }

    private function extractCertificateData(UploadedFile $file): ?array
    {
        return $this->geminiService->extractCertificateDataFromFile($file);
    }

    public function getCities(Province $province)
    {
        $citiesCollection = $province->cities;
        $citiesArray = $citiesCollection->map(function ($city) {
            return [
                'id' => $city->id,
                'cityName' => $city->cityName,
            ];
        });

        return response()->json($citiesArray);
    }
}
