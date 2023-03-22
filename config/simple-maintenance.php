<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
return [
    'minimum_scheduled_at' => env('MAINTENANCE_MINIMUM_SCHEDULED_AT', 'now'),
    'statuses' => [
        'pending' => [
            'code' => 0,
            'name' => 'Pending',
        ],
        'success' => [
            'code' => 1,
            'name' => 'Success',
        ],
        'failed' => [
            'code' => 2,
            'name' => 'Failed',
        ],
        'cancelled' => [
            'code' => 3,
            'name' => 'Cancelled',
        ],
    ],
    'middlewares' => [
        'toggle' => env('MAINTENANCE_TOGGLE_MIDDLEWARE', 'auth:api'),
        'status' => env('MAINTENANCE_STATUS_MIDDLEWARE'),
        'default' => env('MAINTENANCE_DEFAULT_MIDDLEWARE', 'auth:api'),
    ],
];
