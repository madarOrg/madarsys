<?php


return [

    // 'default' => env('BROADCAST_DRIVER', 'redis'),
    'default' => env('BROADCAST_CONNECTION', 'redis'),

'broadcasters' => [
    'reverb' => [
        'driver' => 'socket.io',
        'host' => env('REVERB_HOST', 'http://127.0.0.1'),
        'port' => env('REVERB_PORT', 8080),  // تأكد من أن المنفذ هو 8080 هنا
        'transports' => ['websocket'],
    ],
],

    'redis' => [
        'client' => env('REDIS_CLIENT', 'predis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => '',
        ],
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'pusher' => [
            'driver'  => 'pusher',
            'key'     => env('PUSHER_APP_KEY'),
            'secret'  => env('PUSHER_APP_SECRET'),
            'app_id'  => env('PUSHER_APP_ID'),
            'options' => [
                'cluster'   => env('PUSHER_APP_CLUSTER', 'mt1'),
                'useTLS'    => false,
                'host'      => '127.0.0.1',
                'port'      => 6001,
                'scheme'    => 'http',
            ],
        ],
        'socket.io' => [
        'driver' => 'socket.io',
        'host' => env('BROADCAST_DRIVER_SOCKET_IO_HOST', 'localhost'),
        'port' => env('BROADCAST_DRIVER_SOCKET_IO_PORT', 6001),
    ],
    ],
];
