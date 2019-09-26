# Implement Single Sign On authenticatable credentials for your user models

Create just one unique credential for multiple authenticatable users
that has different eloquent model.

## Installation

```bash
composer require firmantr3/laravel-sso
```

## Configuration

Update your laravel auth config: `/config/auth.php`, and use `sso` provider like so:

```php

    'providers' => [
        'users' => [
            'driver' => 'sso',
            'model' => App\User::class,
        ],
    ],

```

## Sample Usage

User model's class must extend `Firmantr3\LaravelSSO\Models\User` like so:

```php
<?php

namespace App\Admin;

use Firmantr3\LaravelSSO\Models\User;

class Admin extends User
{
    public $timestamps = false;

    protected $table = 'admins';
}

```

You can attach existing users using `attachUser` method on a Credential model like so:

```php
$admin = $credential->attachUser(\App\Admin::create());
```

Or you can use `createAuthenticatableUser` method on a Credential:

```php
$adminClass = \App\Admin::class;
$adminAttributes = [];

$admin = $credential->createAuthenticatableUser($adminClass, $adminAttributes);
```

## Test

```bash
vendor/bin/phpunit
```
