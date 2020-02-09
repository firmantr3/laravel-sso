<?php

namespace Firmantr3\LaravelSSO\Test;

class Member extends BaseUser
{
    public $timestamps = false;

    protected $table = 'members';

    protected $fillable = [
        'points',
    ];
}
