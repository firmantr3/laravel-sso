<?php

namespace Firmantr3\LaravelSSO\Models;

use Firmantr3\LaravelSSO\Traits\UserTrait;
use Illuminate\Foundation\Auth\User as IlluminateUser;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 */
abstract class User extends IlluminateUser 
{

    use UserTrait;

    protected $hidden = [
        'password',
    ];

}
