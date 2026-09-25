<?php

/**
 * EXAMPLE ONLY — prefer includes/db.local.php for Hostinger.
 * secrets.php is still supported and merged under db.local.php.
 *
 *   cp includes/db.local.example.php includes/db.local.php
 */
return [
    'razorpay_key_id' => '',
    'razorpay_key_secret' => '',

    'google_sheet_id' => '',
    'google_sheet_name' => 'eatrrite-website-appointments',
    'google_sheet_tab' => 'Sheet1',
    'google_disabled_slots_tab' => 'disabled-slots',
    'apps_script_url' => '',
    'apps_script_secret' => '',

    'admin_dashboard_password' => '',
    'admin_password' => '',
    'public_base_url' => 'https://red-ferret-338197.hostingersite.com',
    'cron_secret' => '',

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
