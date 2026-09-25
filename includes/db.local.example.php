<?php

/**
 * Copy to db.local.php on the server and fill real values.
 *
 *   cp includes/db.local.example.php includes/db.local.php
 *
 * LOCAL (php -S on your Mac):
 *   Hostinger → Databases → Remote MySQL → add your public IP
 *   host = Hostinger MySQL hostname (NOT localhost)
 *
 * ON HOSTINGER (uploaded site):
 *   host MUST be "localhost"
 */
return [
    'host' => 'srv0000.hstgr.io',
    'port' => 3306,
    'name' => 'eatrrite_db',
    'user' => 'eatrrite',
    'pass' => '',
    'charset' => 'utf8mb4',
    'debug' => true,

    'admin_password' => '',
    'public_base_url' => 'https://red-ferret-338197.hostingersite.com',
    'cron_secret' => '',

    'razorpay_key_id' => '',
    'razorpay_key_secret' => '',

    'apps_script_url' => '',
    'apps_script_secret' => '',
    'google_sheet_id' => '',
    'google_sheet_name' => 'eatrrite-website-appointments',
    'google_sheet_tab' => 'Sheet1',
    'google_disabled_slots_tab' => 'disabled-slots',

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
