<?php

use Carbon\Carbon;

return [
    'host' => env('API_TARGET_HOST', 'http://109.73.206.144:6969/api'),
    'key' => env('API_TARGET_KEY'),

    'endpoints' => [
        'orders' => '/orders',
        'sales' => '/sales',
        'stocks' => '/stocks',
        'incomes' => '/incomes',
    ],

    // date_to = сегодня
    'date_to' => Carbon::now()->format('Y-m-d'),
    // date_from = date_to - 1 год
    'date_from' => Carbon::now()->subYear()->format('Y-m-d'),

    'limit' => 500,
    'request_delay_ms' => 600,

    'timeouts' => [
        'connection' => 10,
        'request' => 30,
    ],

    'retries' => [
        'attempts' => 3,
        'delay_ms' => 100,
    ],
];
