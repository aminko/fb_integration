<?php 

return [
    /*
    |--------------------------------------------------------------------------
    | Application ID
    |--------------------------------------------------------------------------
    |
    | Is available when new app is created on https://developers.facebook.com
    |
    */
    'app-id' => env('FB_APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | Application Secret
    |--------------------------------------------------------------------------
    |
    | Is available when new app is created on https://developers.facebook.com
    |
    */
    'app-secret' => env('FB_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Application Callback URL
    |--------------------------------------------------------------------------
    |
    | URL where user should be redirected after successful login
    |
    */
    'app-callback' => env('FB_APP_CALLBACK')

];