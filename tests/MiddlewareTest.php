<?php 

namespace Firmantr3\LaravelSSO\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firmantr3\LaravelSSO\Test\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Firmantr3\LaravelSSO\Test\TestCase;
use Firmantr3\LaravelSSO\Models\Credential;
use Illuminate\Auth\Middleware\Authenticate;

class MiddlewareTest extends TestCase {

    /** @test */
    public function a_guest_cant_access_auth_route() {
        $this->assertEquals(
            $this->runMiddleware(
                app(Authenticate::class), 'admin'
            ), 'Unauthenticated.');
    }

    /** @test */
    public function logged_in_admin_with_credential_can_access_auth_route() {
        $credential = Credential::create([
            'name' => 'Firman',
            'email' => 'firmantr3@gmail.com',
            'password' => Hash::make('secret'),
        ]);

        $admin = $credential->attachUser($this->testAdmin);

        Auth::guard('admin')->login($admin);

        $this->assertEquals(
            $this->runMiddleware(
                app(Authenticate::class), 'admin'
            ), 200);
    }

    protected function runMiddleware($middleware, $parameter)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
