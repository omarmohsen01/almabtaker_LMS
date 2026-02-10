<?php
return [
    'endpoint'      => env('LRS_ENDPOINT'),
    'middleware'      => ['web', 'auth'],
    'key'    => env('LRS_USERNAME'),
    'secret'    => env('LRS_PASSWORD'),
    'platform_in_arabic'    => env('NELC_PLATFORM_NAME_AR', ''),
    'platform_in_english'    => env('NELC_PLATFORM_NAME_EN', ''),
    'platform_code'         => env('NELC_PLATFORM_CODE', ''),
    'base_route'    => 'nelcxapi/test',
    'enabled'       => env('NELC_XAPI_ENABLED', false),
];
