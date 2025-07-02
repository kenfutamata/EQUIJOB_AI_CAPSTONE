<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key for your Gemini application. This is
    | the key that will be used by the Gemini client to authenticate
    | with the Gemini API.
    |
    */
    'api_key' => env('GEMINI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Gemini HTTP Client
    |--------------------------------------------------------------------------
    |
    | This value is the HTTP client that will be used by the Gemini client
    | to communicate with the Gemini API. You can use any PSR-18
    | compliant HTTP client.
    |
    */
    'http_client' => null,

    /*
    |--------------------------------------------------------------------------
    | Gemini HTTP Client Factory
    |--------------------------------------------------------------------------
    |
    | This value is the HTTP client factory that will be used by the Gemini
    | client to create the HTTP client. You can use any PSR-17
    | compliant HTTP client factory.
    |
    */
    'http_client_factory' => null,

];