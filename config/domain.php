<?php

return [

    'prefix_mode' => env('ENABLE_PREFIX_MODE'), // if true, prefix mode will be used, else will use domain mode

    'web' => [
        'web' => [
            'url' => env('WEB_DOMAIN'),
            'prefix' => '',
            'namespace' => '',
            'route' => [
                'name' => '',
                'file' => 'web.php',
            ]
        ],
        'admin' => [
            'url' => env('WEB_ADMIN_DOMAIN'),
            'prefix' => 'admin',
            'namespace' => 'Admin',
            'route' => [
                'name' => 'admin',
                'file' => 'web/admin.php',
            ]
        ],
        'merchant' => [
            'url' => env('WEB_MERCHANT_DOMAIN'),
            'prefix' => 'merchant',
            'namespace' => 'Merchant',
            'route' => [
                'name' => 'merchant',
                'file' => 'web/merchant.php'
            ]
        ],
    ],

    'api' => [
        'api' => [
            'url' => env('API_DOMAIN'),
            'prefix' => 'v1',
            'namespace' => 'Api',
            'route' => [
                'name' => 'api.v1',
                'file' => 'api/v1/api.php'
            ]
        ],
        'guest' => [
            'url' => env('API_DOMAIN'),
            'prefix' => 'v1',
            'namespace' => 'Api',
            'route' => [
                'name' => 'api.v1',
                'file' => 'api/v1/api.php'
            ]
        ],
        'member' => [
            'url' => env('API_DOMAIN'),
            'prefix' => 'v1',
            'namespace' => 'Api',
            'route' => [
                'name' => 'api.v1',
                'file' => 'api/v1/api.php',
            ]
        ]
    ]
];
