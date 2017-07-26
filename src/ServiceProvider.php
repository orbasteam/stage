<?php
namespace Orbas\Stage;

use \Illuminate\Support\ServiceProvider as Provider;

use Orbas\Stage\Console\EnumMakeCommand;
use Orbas\Stage\Console\PresenterMakeCommand;
use Orbas\Stage\Navigation\Renderer;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootTable();
        $this->bootNavigation();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stage/global.php', 'stage.global');
        
        $this->registerTable();
        $this->registerNavigation();
        $this->registerCommands();
        $this->registerEnum();
        $this->registerRoute();
        
        $this->app->singleton('stage.storage', function($app) {
            $storage = $app['config']['stage.global.field.storage'];
            return (new FieldStorageManager($app))->driver($storage);
        });

        config(['stage.root' => __DIR__]);
        app('view')->addNamespace('stage', config('stage.root') . '/../view/stage/');
    }

    /**
     * Register navigation renderer
     */
    protected function registerNavigation()
    {
        Navigation::rendererResolver(function() {
            return new Renderer();
        });
    }

    /**
     * Register Commands
     */
    protected function registerCommands()
    {
        $this->commands([EnumMakeCommand::class, PresenterMakeCommand::class]);
    }

    protected function bootTable()
    {
        $this->loadViewsFrom(__DIR__ . '/Table/resources/views', 'table');

        if ($this->app->runningInConsole()) {
            
            $this->publishes([
                __DIR__ . '/Table/resources/views' => $this->app->resourcePath('views/vendor/table'),
            ], 'stage-view');

            $this->publishes([
                __DIR__ . '/../config/stage/global.php' => $this->app->configPath('/stage/global.php'),
            ], 'stage-config');
        }
    }

    protected function registerTable()
    {
        $this->app->alias(Table::class, 'table');
    }

    protected function bootNavigation()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/stage/navigation.php' => $this->app->configPath('/stage/navigation.php')
            ], 'stage-config');
            
            $this->publishes([
                __DIR__ . '/../components/navigation/' => $this->app->resourcePath('components/vendor/navigation')
            ], 'stage-component');
        }

    }

    protected function registerRoute()
    {
        $namespace = 'Orbas\Stage\Http\Controllers';
        Route::prefix(config('stage.global.route'))
            ->namespace($namespace)
            ->name('stage-setup.')
            ->group( __DIR__ . '/routes.php');
    }

    protected function registerEnum()
    {
        $this->app->singleton('enum', function($app) {
            return new Enum('App\\Enums');
        });
    }
}