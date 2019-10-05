<?php 

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Models\Credential;

class AuthTest extends TestCase {

    /** @test */
    public function registered_user_login_is_working() {
        $admin = $this->createAdminUserCredential();

        $loginAttempt = auth('admin')->attempt([
            'email' => $admin->email,
            'password' => 'secret',
        ], true);

        $this->assertTrue($loginAttempt);

        /**
         * Cant login via member
         */
        $loginAttempt = auth('member')->attempt([
            'email' => $admin->email,
            'password' => 'secret',
        ], true);

        $this->assertFalse($loginAttempt);

        /**
         * Create member using that credential, then attempt login
         */
        /** @var \Firmantr3\LaravelSSO\Models\Credential $credential */
        $credential = $admin->credential;
        $credential->createAuthenticatableUser(Member::class, [
            'points' => 99,
        ]);
        $loginAttempt = auth('member')->attempt([
            'email' => $admin->email,
            'password' => 'secret',
        ], true);

        $this->assertTrue($loginAttempt);

        /**
         * Just one credential, two different user endpoint
         */
        $this->assertEquals(1, Credential::count());
    }

}
