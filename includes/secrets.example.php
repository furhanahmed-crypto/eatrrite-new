<?php

/**
 * EXAMPLE ONLY — copy to secrets.php on the server and fill in real values.
 * secrets.php is gitignored and must never be committed.
 *
 *   cp includes/secrets.example.php includes/secrets.php
 */
return [
    'razorpay_key_id' => '',
    'razorpay_key_secret' => '',

    'google_sheet_id' => '',
    'google_sheet_name' => 'eatrrite-website-appointments',
    'google_sheet_tab' => 'Sheet1',
    'google_slot_times_tab' => 'slot-times-config',
    'google_disabled_slots_tab' => 'disabled-slots',
    'apps_script_url' => '',
    'apps_script_secret' => '',

    'admin_dashboard_password' => '',

    'mail' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => '',
        'password' => '',
        'from_email' => '',
        'from_name' => 'Eat Rrite',
        'admin_email' => '',
        'admin_name' => 'Eat Rrite Team',
    ],
];
