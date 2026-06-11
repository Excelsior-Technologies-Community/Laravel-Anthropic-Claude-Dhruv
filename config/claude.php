<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Anthropic API Key
    |--------------------------------------------------------------------------
    |
    | Your Anthropic API key. You can get one from:
    | https://console.anthropic.com/
    |
    */
    'api_key' => env('ANTHROPIC_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Anthropic API. You generally don't need to change
    | this unless you're using a proxy or custom endpoint.
    |
    */
    'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests. Claude can take some time
    | to generate responses, especially for complex prompts.
    |
    */
    'timeout' => (float) env('ANTHROPIC_TIMEOUT', 30.0),

    /*
    |--------------------------------------------------------------------------
    | Maximum Retries
    |--------------------------------------------------------------------------
    |
    | The number of times to retry a failed request. The SDK uses exponential
    | backoff for retryable errors.
    |
    */
    'max_retries' => (int) env('ANTHROPIC_MAX_RETRIES', 2),

    /*
    |--------------------------------------------------------------------------
    | Custom Headers
    |--------------------------------------------------------------------------
    |
    | Additional headers to send with every API request. Useful for tracking,
    | custom authentication, or proxy requirements.
    |
    */
    'headers' => [
        // 'X-Custom-Header' => 'value',
    ],
];

