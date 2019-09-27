<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Exceptions\CredentialModelException;
use Firmantr3\LaravelSSO\Models\Authenticatable;
use Firmantr3\LaravelSSO\Models\Credential;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Optional;

class ModelsTest extends TestCase
{

    /** @test */
    public function it_should_able_to_create_admin_credential() {
        $admin = $this->createAdminUserCredential();

        $authenticatable = $admin->authenticatable;

        $credential = $authenticatable->credential;

        $this->assertTrue($admin instanceof Admin);
        $this->assertTrue($credential->authenticatables->first()->user instanceof Admin);
        $this->assertTrue($authenticatable instanceof Authenticatable);
        $this->assertTrue($credential instanceof Credential);
        $this->assertTrue($authenticatable->credential instanceof Credential);
        
        $this->assertTrue($credential->authenticatables instanceof Collection);
        $this->assertTrue($credential->authenticatables() instanceof HasMany);

        $this->assertTrue(is_integer($credential->id));
        $this->assertTrue(is_integer($admin->id));
        $this->assertTrue(is_integer($admin->authenticatable->id));
    }

    /** @test */
    public function it_throws_an_exception_if_credential_has_duplicate_user() {
        $this->expectException(CredentialModelException::class);

        $admin = $this->createAdminUserCredential();

        $admin = $admin->authenticatable->credential->createAuthenticatableUser(Admin::class);
    }

    /** @test */
    public function credential_can_filter_by_associated_user_model() {
        $this->createAdminUserCredential();

        $credential = Credential::userModel(Admin::class)->first();

        $this->assertTrue($credential instanceof Credential);
    }

    /** @test */
    public function existing_user_model_can_be_attached_to_exsisting_credential() {
        $credential = $this->createCredential();

        $credential->attachUser($this->testAdmin);

        $this->assertEquals($credential->authenticatables->first()->user, $this->testAdmin);
    }

    /** @test */
    public function credential_password_should_be_hidden_when_serialized() {
        $admin = $this->createAdminUserCredential();

        $credential = $admin->authenticatable->credential;

        $this->assertArrayNotHasKey('password', $credential->toArray());
        $this->assertArrayNotHasKey('password', $admin->toArray());
    }

    /** @test */
    public function user_should_have_credential_attributes() {
        $admin = $this->createAdminUserCredential();
        $admin->refresh();
        $credential = $admin->credential;
        $admin->append([
            'name',
            'email',
            'password',
        ]);

        $this->assertTrue($credential instanceof Optional);
        $this->assertEquals($credential->name, $admin->name);
        $this->assertEquals($credential->email, $admin->email);
        $this->assertEquals($credential->password, $admin->password);
        $this->assertEquals($credential->name, $admin->getNameAttribute());
        $this->assertEquals($credential->email, $admin->getEmailAttribute());
        $this->assertEquals($credential->password, $admin->getPasswordAttribute());
    }

}
