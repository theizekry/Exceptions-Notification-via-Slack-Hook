<?php

return [

    /*
    |------------------------------------------------------------------------------------------------------
    | Here You can set your Slack Web-Hook Configurations to push notification when any exception occurred.
    |------------------------------------------------------------------------------------------------------
    |
    */

    'slack-exception-hook-url' => env('SLACK_WEB_HOOK_EXCEPTIONS_URL', null),
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
            "request_headers"   => false, // Should not be most time enabled for Security Reasons
            "ip_address"        => true,
        ]
    /*
    |------------------------------------------------------------------------------------------------------
    | Here You can set your Slack Web-Hook Configurations to push notification when any exception occurred.
    |------------------------------------------------------------------------------------------------------
    */
];

