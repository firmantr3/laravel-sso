<?php

namespace Firmantr3\LaravelSSO\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Firmantr3\LaravelSSO\Providers\SSOUserProvider;

class SSOServiceProvider extends ServiceProvider
{

    protected function ssoConfigPath() {
        return __DIR__ . '/../../../../config/sso.php';
    }

    /**
     * @return string
     */
    public function tableMigrationPath() {
        return __DIR__.'/../../../../database/migrations/create_laravel_sso_table.php.stub';
    }

    /**
     * Register Laravel SSO Service.
     *
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            $this->tableMigrationPath() => $this->getMigrationFileName($filesystem),
        ], 'migrations');

        $this->publishes([
            $this->ssoConfigPath() => config_path('sso.php'),
        ], 'config');

        Auth::provider('sso', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new SSOUserProvider(
                $app->make(Hasher::class),
                $config['model']
            );
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_laravel_sso_table.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_laravel_sso_table.php")
            ->first();
    }

    public function register() {
        $this->mergeConfigFrom($this->ssoConfigPath(), 'sso');
    }
}
