<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binance authentication
    |--------------------------------------------------------------------------
    |
    | Authentication key and secret for Binance API.
    |
     */

    'auth' => [
        'key'        => env('BINANCE_KEY', 'lpvYLdbGK3QbOQHyCxaTjH6dmbYyQxL8U16Lwo9AcqEU6nkQkaK34vt98N5BvtPz'),
        'secret'     => env('BINANCE_SECRET', 'LFoVNzodMLInlDcqY0kDzWTuJPOTckv1OmGrqILEgN4iBu78d4waHChNDFJxn3xy'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | Binance API endpoints
    |
     */

    'urls' => [
        'api'   => 'https://api.binance.com/api/',
        'sapi'  => 'https://api.binance.com/sapi/',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | Binance API settings
    |
     */

    'settings' => [
        'timing' => env('BINANCE_TIMING', 5000),
    ],

];
