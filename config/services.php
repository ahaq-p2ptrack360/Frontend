<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'agent_chat' => [
        'enabled' => env('AGENT_CHAT_ENABLED', true),
        'provider' => env('AGENT_CHAT_PROVIDER', 'ollama'),
        'url' => env('AGENT_CHAT_URL', 'http://localhost:11434/api/generate'),
        'api_key' => env('AGENT_CHAT_API_KEY'),
        'model' => env('AGENT_CHAT_MODEL', 'llama3.1'),
        'chat_url' => env('AI_CHAT_URL'),
        'plan_url' => env('AI_PLAN_URL'),
    ],

];
