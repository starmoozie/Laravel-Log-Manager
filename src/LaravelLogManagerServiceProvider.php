<?php

namespace Starmoozie\LaravelLogManager;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Route;

class LaravelLogManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/starmoozie/logmanager.php';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // LOAD THE VIEWS
        // - first the published/overwritten views (in case they have any changes)
        $customViewsFolder = resource_path('views/vendor/starmoozie/logmanager');

        if (file_exists($customViewsFolder)) {
            $this->loadViewsFrom($customViewsFolder, 'logmanager');
        }
        // - then the stock views that come with the package, in case a published view might be missing
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'logmanager');

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/starmoozie/logmanager.php',
            'starmoozie.logmanager'
        );

        // publish config file
        $this->publishes([__DIR__.'/config' => config_path()], 'config');

        // publish lang files
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/starmoozie')], 'lang');
        // publish the views
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/starmoozie/logmanager')], 'views');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/starmoozie, use that one
        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLaravelLogManager();
        $this->setupRoutes($this->app->router);
    }

    private function registerLaravelLogManager()
    {
        $this->app->bind('logmanager', function ($app) {
            return new LaravelLogManager($app);
        });
    }
}
