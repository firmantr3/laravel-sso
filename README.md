# Implement Single Sign On (SSO) authenticatable credentials for your user models

Create just one unique credential for multiple authenticatable users that has different eloquent model.

## Introduction

Given you need to implement a school management application, that have 3 different user: admins, teachers, students. Sometimes, admins can be teacher as well, how if the email and password don't need to be different? Well, **Laravel SSO** to the rescue!

## Installation

```bash
composer require firmantr3/laravel-sso
```

Publish migration by running artisan `vendor:publish`:

```bash
php artisan vendor:publish
```

## Configuration

Update your laravel auth config: `/config/auth.php`, and use `sso` provider, like so:

```php
    'providers' => [
        'admins' => [
            'driver' => 'sso',
            'model' => App\Admin::class,
        ],

        'teachers' => [
            'driver' => 'sso',
            'model' => App\Teacher::class,
        ],

        'students' => [
            'driver' => 'sso',
            'model' => App\Student::class,
        ],
    ],
```

You can use preset Credential model: `Firmantr3\LaravelSSO\Models\Credential`.

## Sample Usage

Your user models must have `credential_id` AND Laravel's default user attributes like `remember_token`, check your user model migration. The `credential_id` should be unique for each models, to prevent duplication.

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
$credential = Firmantr3\LaravelSSO\Models\Credential::first();

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
