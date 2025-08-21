<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'accounts',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'accounts',
        ],
    ],
    

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Account::class, // <-- sửa tại đây
        ],
        'accounts' => [
            'driver' => 'eloquent',
            'model' => App\Models\Account::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // hoặc password_reset_tokens (Laravel 11)
            'expire' => 60, // thời gian hiệu lực token (phút)
            'throttle' => 60,
        ],
        'accounts' => [
            'provider' => 'accounts',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
