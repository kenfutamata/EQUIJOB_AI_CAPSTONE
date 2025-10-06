<?php

namespace App\Services;


use Google_Client;
use Google_Service_Meet;
use Google_Service_Meet_Space;
use Google_Service_Meet_SpaceConfig;
use Illuminate\Support\Facades\Log;

class GoogleMeetService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setAccessType('offline');
    }

    public function createMeeting(): string
    {
        $masterRefreshToken = env('GOOGLE_MASTER_REFRESH_TOKEN');

        if (!$masterRefreshToken) {
            Log::error('CRITICAL: GOOGLE_MASTER_REFRESH_TOKEN is not set in the .env file.');
            throw new \Exception('Application is not configured for meeting creation.');
        }

        try {
            $accessToken = $this->client->fetchAccessTokenWithRefreshToken($masterRefreshToken);

            if (isset($accessToken['error'])) {
                Log::error('Google API Token Refresh Failed. This usually means the refresh token is invalid.', [
                    'error' => $accessToken['error'],
                    'error_description' => $accessToken['error_description'] ?? 'No description provided by Google.'
                ]);
                throw new \Exception('Authentication service failed. The refresh token may be invalid or expired.');
            }

            $this->client->setAccessToken($accessToken);

            $meetService = new Google_Service_Meet($this->client);
            

            $spaceConfig = new Google_Service_Meet_SpaceConfig();
            

            $spaceConfig->setAccessType('OPEN');

            // 3. Create the main space object.
            $space = new Google_Service_Meet_Space();
            
            $space->setConfig($spaceConfig);
            
            $createdSpace = $meetService->spaces->create($space);

            return $createdSpace->getMeetingUri();
            
        } catch (\Exception $e) {
            Log::error('Failed to create Google Meet link due to an exception.', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}