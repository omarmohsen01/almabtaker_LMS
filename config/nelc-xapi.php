<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NELC xAPI LRS Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('NELC_XAPI_ENABLED', true),

    'endpoint' => env('LRS_ENDPOINT', 'https://lrs.nelc.gov.sa/lrs-license-stg/xapi/statements'),

    'key' => env('LRS_USERNAME', ''),

    'secret' => env('LRS_PASSWORD', ''),

    // Platform code assigned by NELC (used in context.platform)
    'platform_code' => env('NELC_PLATFORM_CODE', 'lms.almabtaker.com'),

    // Platform names
    'platform_in_arabic' => env('NELC_PLATFORM_AR', 'المبتكر'),
    'platform_in_english' => env('NELC_PLATFORM_EN', 'Almabtaker'),

    // LMS base URL
    'lms_url' => env('APP_URL', 'https://lms.almabtaker.com'),

    // Language
    'language' => 'ar-SA',
];
