<?php

namespace App\Services;
use Illiminate\Support\Facades\Http;
use Illuminate\Support\Facades\Http as FacadesHttp;

class OpenAIService{
    public function generateSummary(string $input): string 
    {
        $response = FacadesHttp::withToken(config('services.openai.api_key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $input],
                ],
                'max_tokens' => 100,
            ]);
        return $response->json()['choices'][0]['message']['content'] ?? 'No response from OpenAI';
    }
}

?>