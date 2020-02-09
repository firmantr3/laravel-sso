<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Models\User;

class Admin extends User
{

    public $timestamps = false;

    protected $table = 'admins';
    
}
