<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Test\Admin;
use Firmantr3\LaravelSSO\Test\Member;
use Firmantr3\LaravelSSO\Models\Credential as ModelsCredential;

class Credential extends ModelsCredential
{

    public function admins() {
        return $this->hasMany(Admin::class);
    }

    public function members() {
        return $this->hasMany(Member::class);
    }

}
