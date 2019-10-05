<?php

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Test\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Schema\Blueprint;
use Firmantr3\LaravelSSO\Models\Credential;
use Orchestra\Testbench\TestCase as Orchestra;
use Firmantr3\LaravelSSO\Providers\SSOServiceProvider;

abstract class TestCase extends Orchestra
{

    /** @var \Firmantr3\LaravelSSO\Test\Admin */
    protected $testAdmin;

    public function setUp(): void
    {
        parent::setUp();

        // Note: this also flushes the cache from within the migration
        $this->setUpDatabase($this->app);

        $this->testAdmin = Admin::first();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SSOServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set-up admin guard
        $app['config']->set('auth.guards.admin', ['driver' => 'session', 'provider' => 'admins']);
        $app['config']->set('auth.providers.admins', ['driver' => 'sso', 'model' => Admin::class]);

        // Set-up member guard
        $app['config']->set('auth.guards.member', ['driver' => 'session', 'provider' => 'members']);
        $app['config']->set('auth.providers.members', ['driver' => 'sso', 'model' => Member::class]);

        $app['config']->set('cache.prefix', 'firmantr3_tests---');
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('admins', function (Blueprint $table) {
            $table->increments('id');
        });
        $app['db']->connection()->getSchemaBuilder()->create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('points');
        });

        include_once __DIR__.'/../database/migrations/create_laravel_sso_table.php.stub';

        (new \CreateLaravelSSOTable())->up();

        Admin::create([]);
        Member::create([
            'points' => 0,
        ]);
    }

    /** @return Admin */
    protected function createAdminUserCredential() {
        $credential = $this->createCredential();

        $admin = $credential->createAuthenticatableUser(Admin::class);

        return $admin;
    }

    /** @return Credential */
    protected function createCredential() {
        return Credential::create([
            'name' => 'Firman',
            'email' => 'firmantr3@gmail.com',
            'password' => Hash::make('secret'),
        ]);
    }
}
