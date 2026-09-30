# Hostinger config recheck

Database operations fail until MySQL login matches the Hostinger database. Recheck these two files on the server. Do not upload the Mac copies.

## `includes/db.local.php`

```php
'host' => 'localhost',
'name' => 'YOUR_DATABASE_NAME',
'user' => 'YOUR_DATABASE_USER',
'pass' => 'YOUR_DATABASE_PASSWORD',
'debug' => false,
'public_base_url' => 'https://www.eatrrite.com',
```

## `includes/secrets.php`

```php
'public_base_url' => 'https://www.eatrrite.com',
```

Reload https://www.eatrrite.com/appointment.php and open the slot picker. Dates should load.
