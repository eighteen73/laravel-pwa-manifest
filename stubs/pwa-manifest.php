<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | This is typically the APP_URL but if you are generating the files offline
    | for upload it might be different.
    |
    */

    'url' => env('APP_URL'),

    /*
    |--------------------------------------------------------------------------
    | Root path
    |--------------------------------------------------------------------------
    |
    | This is where all generated files will be stored. It must be publicly
    | accessible from the public_path setting below.
    |
    | In most cases you will want to ignore this directory in the project's
    | Git repository.
    |
    | WARNING: THIS DIRECTORY IS WIPED CLEAN EACH TIME FILES ARE REGENERATED!
    |          IT MUST BE A UNIQUE DIRECTORY.
    |
    */

    'root_path' => public_path('pwa-manifest'),

    /*
    |--------------------------------------------------------------------------
    | Root URI
    |--------------------------------------------------------------------------
    |
    | This is where all generated files will be accessed by web browsers..
    |
    */

    'public_path' => '/pwa-manifest',

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    |
    | Your source image files that are used to generate icons.
    |
    | These do not need to be web-accessible.
    |
    */

    'icons' => [
        'primary' => resource_path('icons/primary.png'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Manifest options
    |--------------------------------------------------------------------------
    |
    | These are some basic settings that will be applied to the manifest..
    |
    */

    'name' => env('APP_NAME'),

    'short_name' => env('APP_NAME'),

    'description' => null,

    'theme_color' => '#ffffff',

    /*
    |--------------------------------------------------------------------------
    | Manifest overrides
    |--------------------------------------------------------------------------
    |
    | Any content added to this array will be added to your generated manifest
    | file. All keys are optional and should only be added to override package
    | defaults or add extra functionality.
    |
    | We recommend you first generate the default manifest and then fill out
    | this option depending on what changes you need.
    |
    | For example:
    |   'orientation' => 'portrait',
    |   'start_url' => env('APP_URL').'/dashboard',
    |   'shortcuts' => [
    |     [
    |       'name' => 'My Account',
    |       'url' => env('APP_URL').'/account'
    |     ]
    |   ],
    |
    | Ref. https://developer.mozilla.org/en-US/docs/Web/Manifest
    |
    */

    'manifest_overrides' => [
        //
    ],

];
