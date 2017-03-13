<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook'=>[
        'client_id'=>'200093723788409',
        'client_secret'=>'e691b2b3d9025cacf0b491e168d7842a',
        'redirect'=>'http://dadalee.app/auth/fb/callback'
    ],

    'google'=>[
        'client_id'=>'854089819380-ftntrv0j3ag06e6ou52dfi5f9g71p4jf.apps.googleusercontent.com',
        'client_secret'=>'5ABSD-Np7O6q9C0XZO2B_oQv',
        'redirect'=>'http://dadalee.app/auth/google/callback'
    ],

];
