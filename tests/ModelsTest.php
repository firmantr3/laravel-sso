<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Exceptions\CredentialModelException;
use Illuminate\Support\Facades\Hash;
use Firmantr3\LaravelSSO\Models\Credential;

class ModelsTest extends TestCase
{

    /** @test */
    public function it_should_able_to_create_admin_credential() {
        $credential = Credential::create([
            'name' => 'Firman',
            'email' => 'firmantr3@gmail.com',
            'password' => Hash::make('secret'),
        ]);

        $admin = $credential->createAuthenticatableUser(Admin::class);

        $this->assertTrue($admin instanceof Admin);
        $this->assertTrue($credential->authenticatables->first()->user instanceof Admin);
    }

    /** @test */
    public function it_throws_an_exception_if_credential_has_duplicate_user() {
        $this->expectException(CredentialModelException::class);

        $credential = Credential::create([
            'name' => 'Firman',
            'email' => 'firmantr3@gmail.com',
            'password' => Hash::make('secret'),
        ]);

        $admin = $credential->createAuthenticatableUser(Admin::class);

        $admin = $credential->createAuthenticatableUser(Admin::class);
    }
}
