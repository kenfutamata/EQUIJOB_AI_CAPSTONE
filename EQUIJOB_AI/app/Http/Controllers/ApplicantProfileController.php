<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Province;
use App\Models\users; // Corrected namespace for User model
use App\Services\GeminiService;
use App\Services\OpenAIService;
use App\Services\SupabaseStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicantProfileController extends Controller
{
    protected $aiVisionService;

    // CHANGED: Inject the new OpenAIService here
    public function __construct(OpenAIService $aiVisionService)
    {
        $this->aiVisionService = $aiVisionService;
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

        $response = response()->view('users.applicant.applicant_profile', compact('user', 'notifications', 'unreadNotifications', 'provinces', 'cities'));
        $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        return $response;
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
    // --- END of unchanged methods ---

    public function update(Request $request, SupabaseStorageService $supabase)
    {
        $user = Auth::guard('applicant')->user();
        // Validation is unchanged and correct
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
            'certificates.*' => 'file|mimes:jpg,jpeg,png,pdf|max:4096',
            'certificates_to_delete' => 'nullable|array',
            'certificates_to_delete.*' => 'string|url',
        ]);

        try {
            // Certificate Deletion is unchanged and correct
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

            // --- Certificate Upload & AI Extraction with OpenAI ---
            $newCertificateUrls = [];
            $newlyExtractedData = [];
            $failedFiles = [];

            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $file) {
                    try {
                        // =========================================================================
                        // === FINAL CHANGE: Call the new service instead of the old one ===
                        // =========================================================================
                        $extractedData = $this->aiVisionService->extractCertificateDataFromFile(
                            $file,
                            $user->firstName,
                            $user->lastName
                        );

                        if ($extractedData === null) {
                            throw new \Exception("AI service failed to process the file.");
                        }

                        // The rest of the logic is unchanged and correct
                        $url = $supabase->upload($file, 'certificates');
                        if ($url) {
                            $newCertificateUrls[] = $url;
                            $extractedData['url'] = $url;
                            $newlyExtractedData[] = $extractedData;
                        } else {
                            $failedFiles[$file->getClientOriginalName()] = 'upload to storage failed';
                            Log::error('Supabase upload failed for a valid certificate: ' . $file->getClientOriginalName());
                        }

                    } catch (CertificateNameMismatchException $e) {
                        $failedFiles[$file->getClientOriginalName()] = 'the name on the certificate did not match';
                        Log::warning('Certificate Rejected (Name Mismatch): ' . $e->getMessage(), [
                            'user_id' => $user->id,
                            'file' => $file->getClientOriginalName(),
                        ]);
                    } catch (\Exception $e) {
                        $failedFiles[$file->getClientOriginalName()] = 'it could not be read or verified';
                        Log::error('Certificate Rejected (Processing Error): ' . $e->getMessage(), [
                             'user_id' => $user->id,
                             'file' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
            
            // --- Update User Model (unchanged and correct) ---
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

            // --- Construct Message (unchanged and correct) ---
            $successMessage = 'Profile and skills updated successfully.';
            if (!empty($failedFiles)) {
                $warningParts = [];
                foreach ($failedFiles as $fileName => $reason) {
                    $warningParts[] = "{$fileName} ({$reason})";
                }
                $successMessage .= ' However, the following files were rejected: ' . implode(', ', $warningParts) . '.';
            }

            return redirect()->back()->with('Success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Profile Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred while updating the profile.');
        }
    }
}