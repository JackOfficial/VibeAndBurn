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

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT')
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'flutterwave' => [
       'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
       'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
       'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
    ],

    'bulkmedya' => [
    'key' => env('BULKMEDYA_API_KEY'),
    ],

    'amazing' => [
        'key' => env('AMAZING_SMM_API_KEY'),
    ],

    'bulkfollows' => [
        'key' => env('BULKFOLLOWS_API_KEY'),
    ],

    'smmsun' => [
        'key' => env('SMMSUN_API_KEY'),
    ],

];
