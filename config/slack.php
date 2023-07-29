<?php

return [

    /*
    |------------------------------------------------------------------------------------------------------
    | Here You can set your Slack Web-Hook Configurations to push notification when any exception occurred.
    |------------------------------------------------------------------------------------------------------
    |
    */

    'slack-exception-hook-url' => env('SLACK_WEB_HOOK_EXCEPTIONS_URL', 'https://hooks.slack.com/services/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'),
    'push-notification-message' =>
        [
            "getCode"           => true,
            "getMessage"        => true,
            "getFile"           => true,
            "getLine"           => true,
            "getDateWithTime"   => true,
            "getPrevious"       => true,
            "getTraceAsString"  => true,
            "getTrace"          => true,
            "getRequestData"    => true,
            "full_url"          => true,
            "request_headers"   => true,
        ]
    /*
    |------------------------------------------------------------------------------------------------------
    | Here You can set your Slack Web-Hook Configurations to push notification when any exception occurred.
    |------------------------------------------------------------------------------------------------------
    */
];

