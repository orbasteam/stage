<?php
namespace Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Mockery;
use Orbas\Stage\ServiceProvider;

class StageTestCase extends AbstractPackageTestCase
{
    protected function getServiceProviderClass($app)
    {
        return ServiceProvider::class;
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        
        $app->config->set('stage.global', require 'config/global.php');
    }

    /**
     * @param string $class
     * @param string|null $abstract
     *
     * @return Mockery\MockInterface
     */
    public function initMock($class, $abstract = null)
    {
        if (!$abstract) {
            $abstract = $class;
        }
        
        $mock = Mockery::mock($class);
        $this->app->instance($abstract, $mock);

        return $mock;
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        $this->tearDownMockery();
    }
}