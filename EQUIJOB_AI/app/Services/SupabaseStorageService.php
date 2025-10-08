<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class SupabaseStorageService
{
    protected $baseUrl;
    protected $apiKey;
    protected $bucket;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->apiKey = config('services.supabase.key');
        $this->bucket = 'equijob_storage'; // ✅ match your Supabase bucket
    }

    public function upload(UploadedFile $file, $folder = '')
    {
        // Clean and UTF-8 safe filename
        $originalName = $file->getClientOriginalName();
        $safeName = preg_replace('/[^\w\-.]+/u', '_', $originalName); // remove special chars
        $fileName = time() . '_' . $safeName;
        $path = trim($folder . '/' . $fileName, '/');

        // Encode bucket name for URL safety
        $encodedBucket = rawurlencode($this->bucket);

        // Send binary body safely (not JSON-encoded)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'apikey' => $this->apiKey,
            'Content-Type' => $file->getMimeType(),
        ])->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
          ->put("{$this->baseUrl}/storage/v1/object/{$encodedBucket}/{$path}");

        if (!$response->successful()) {
            throw new \Exception("Supabase upload failed: " . $response->body());
        }

        // Return the public URL
        return "{$this->baseUrl}/storage/v1/object/public/{$encodedBucket}/{$path}";
    }
}
