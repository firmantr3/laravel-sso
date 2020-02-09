<?php 

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Models\User;
use Firmantr3\LaravelSSO\Test\Credential;

abstract class BaseUser extends User {
    

    /** @return string */
    public function credentialClass() {
        return Credential::class;
    }

}
