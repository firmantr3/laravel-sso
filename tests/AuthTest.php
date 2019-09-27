<?php 

namespace Firmantr3\LaravelSSO\Test;

class AuthTest extends TestCase {

    /** @test */
    public function registered_user_login_is_working() {
        $admin = $this->createAdminUserCredential();

        $loginAttempt = auth('admin')->attempt([
            'email' => $admin->email,
            'password' => 'secret',
        ], true);

        $this->assertTrue($loginAttempt);
    }

}
