<?php
namespace App\Console\Commands\Traits;

use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait UsesTimeApi
{
    protected function getCurrentTimeFromApi(): ?Carbon
    { 
        try {  
            $response = Http::get('https://www.timeapi.io/api/Time/current/zone?timeZone=UTC'); 
            if($response ->successful()){
                $data = $response->json();
                return Carbon::parse($data['dateTime']);
            }
            $this->error('Failed to fetch time data. Status: ' . $response->status());
            Log::error('Failed to fetch time data. Status: ' . $response->status());
            return null;
        } catch (\Exception $e) {
            $this->error('Error fetching time data: ' . $e->getMessage());
            Log::critical('Error fetching time data: ' . $e->getMessage());
            return null;
        }
    }
}