<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'custom-error' => [
            'driver' => 'stack',
            'channels' => (in_array(env('APP_ENV'), ['local','testing']) ? ['daily'] : ['slack','daily']),
        ],

        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'call_logging' => [
            'driver' => 'single',
            'path' => storage_path('logs/call.log'),
            'level' => 'debug',
        ],
        'payment' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment.log'),
            'level' => 'debug',
        ],
        'otp' => [
            'driver' => 'single',
            'path' => storage_path('logs/OTP.log'),
            'level' => 'debug',
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL', 'https://hooks.slack.com/services/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'),
            'username' => config('app.name').config('app.env').' ON '.config('app.url'),
            'emoji' => ':boom:',
            'level' => 'debug',
        ],

        'slack_no_gp' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_NO_GP_WEBHOOK_URL', 'https://hooks.slack.com/services/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'),
            'username' => config('app.env').' ON '.config('app.url'),
            'emoji' => ':boom:',
            'level' => 'debug',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
