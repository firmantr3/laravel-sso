<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Models\User;

class Member extends User
{
    public $timestamps = false;

    protected $table = 'members';

    protected $fillable = [
        'points',
    ];
}
