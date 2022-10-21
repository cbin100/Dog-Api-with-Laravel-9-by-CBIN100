<?php
    return [
        'mode'    => env('PAYPAL_MODE', 'sandbox'),
        'admin' => [
            'option_menu_status' => [
                ''          => '-- Select Status --',
                'publish'    => 'Publish',
                'inherit'   => 'Inherit',
                'trush'     => 'Trush',
                'private'   => 'Private',
            ],
            'option_menu_position' => [
                ''              => '-- Select Position --',
                'top-left'      => 'Top Left',
                'top-right'     => 'Top Right',
                'nav-left'      => 'Navbar Left',
                'nav-right'     => 'Navbar Right',
                'left'          => 'Left',
                'right'         => 'Right',
                'middle-left'   => 'Middle Left',
                'middle-right'  => 'Middle Right',
                'bottom-left'   => 'Bottom Left',
                'bottom-right'  => 'Bottom Right',
                'foot-left'     => 'Foot Left',
                'foot-right'    => 'Foot Right',
            ],

            'app_id'            => 'APP-80W284485P519543T',
        ],
        'live' => [
            'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
            'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
            'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
        ],

        'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
        'currency'       => env('PAYPAL_CURRENCY', 'USD'),
        'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
        'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
        'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
    ];
