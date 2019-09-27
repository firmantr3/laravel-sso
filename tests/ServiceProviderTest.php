<?php 

namespace Firmantr3\LaravelSSO\Test;

use Firmantr3\LaravelSSO\Providers\SSOServiceProvider;

class ServiceProviderTest extends TestCase {

    /** @var SSOServiceProvider */
    protected $serviceProvider;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->serviceProvider = new SSOServiceProvider($this->app);
    }

    /** @test */
    public function table_migration_path_is_exist() {
        $this->assertTrue(file_exists($this->serviceProvider->tableMigrationPath()));
    }

}
