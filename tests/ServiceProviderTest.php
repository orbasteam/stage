<?php
namespace Tests;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Orbas\Stage\ServiceProvider;

class ServiceProviderTest extends StageTestCase
{
    use ServiceProviderTrait;

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return ServiceProvider::class;
    }

    /**
     * @test
     * @group ServiceProvider
     */
    public function it_should_have_3_config_keys()
    {
        $config = $this->app->config->get('stage.global');
        $keys   = ['modelNamespace', 'field', 'route'];
        
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $config);
        }
    }
}