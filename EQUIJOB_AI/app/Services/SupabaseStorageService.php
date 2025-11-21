<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $baseUrl;
    protected $apiKey;
    protected $bucket;
    // We are removing the unused $client property

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->apiKey = config('services.supabase.key');
        $this->bucket = 'equijob_storage';
    }

    public function upload(UploadedFile $file, $folder = '')
    {
        $originalName = $file->getClientOriginalName();
        $safeName = preg_replace('/[^\w\-.]+/u', '_', $originalName);
        $fileName = time() . '_' . $safeName;
        $path = trim($folder . '/' . $fileName, '/');

        $encodedBucket = rawurlencode($this->bucket);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'apikey' => $this->apiKey,
                'Content-Type' => $file->getMimeType(),
            ])
            // =========================================================================
            // === THIS IS THE FIX: Give the upload 120 seconds (2 minutes) to complete. ===
            // =========================================================================
            ->timeout(120)
            ->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
            ->put("{$this->baseUrl}/storage/v1/object/{$encodedBucket}/{$path}");

            if (!$response->successful()) {
                Log::error("Supabase upload failed: " . $response->body());
                return null; // Return null on failure instead of throwing an exception
            }

            // Return the public URL for the newly uploaded file
            return "{$this->baseUrl}/storage/v1/object/public/{$encodedBucket}/{$path}";

        } catch (\Exception $e) {
            Log::error('Supabase upload exception: ' . $e->getMessage());
            return null;
        }
    }

    public function delete(string $path)
    {
        if (empty($path)) {
            return false;
        }

        $encodedBucket = rawurlencode($this->bucket);

        try {
            // IMPROVEMENT: Using the Http client for consistency with the upload method.
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'apikey' => $this->apiKey,
            ])
            ->timeout(60) // Also give the delete request a generous timeout
            ->delete("{$this->baseUrl}/storage/v1/object/{$encodedBucket}", [
                'prefixes' => [$path]
            ]);

            if ($response->failed()) {
                Log::error('Supabase Delete Error: ' . $response->body());
                return false;
            }
            
            Log::info('Supabase: Successfully deleted file at path: ' . $path);
            return true;

        } catch (\Exception $e) {
            Log::error('Supabase Delete Exception: ' . $e->getMessage());
            return false;
        }
    }
}