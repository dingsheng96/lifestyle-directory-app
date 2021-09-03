<?php

return [

    'prefix' => false, // if true, prefix mode will be used, else will use domain mode

    'web' => [
        'admin' => [
            'url' => env('WEB_ADMIN_DOMAIN'),
            'prefix' => 'admin',
            'namespace' => 'Admin',
            'route' => [
                'name' => 'admin',
                'file' => 'admin.php',
            ]
        ],
        'merchant' => [
            'url' => env('WEB_MERCHANT_DOMAIN'),
            'prefix' => 'merchant',
            'namespace' => 'Merchant',
            'route' => [
                'name' => 'merchant',
                'file' => 'merchant.php'
            ]
        ],
    ],

    'api' => [
        'guest' => [
            'url' => env('API_DOMAIN'),
            'prefix' => 'api',
            'namespace' => 'Api',
            'version' => 'v1',
            'route' => [
                'name' => 'api',
                'file' => 'api.php'
            ]
        ],
        'member' => [
            'url' => env('API_DOMAIN'),
            'prefix' => 'api',
            'namespace' => 'Api',
            'version' => 'v1',
            'route' => [
                'name' => 'api',
                'file' => 'api.php'
            ]
        ]
    ]
];
