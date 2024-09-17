<?php

namespace SpondonIt\Service;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use SpondonIt\Service\Console\Commands\MigrateStatusCommand;
use SpondonIt\Service\Middleware\ServiceMiddleware;

class SpondonItServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::macro('isInstalled',function (){
            return  Storage::exists('.app_installed') &&  Storage::get('.app_installed');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', ServiceMiddleware::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'service');
        $this->loadViewsFrom(resource_path('/views/vendors/service'), 'service');

        $this->publishes([
            __DIR__.'/..' => public_path('vendor/spondonit'),
             __DIR__.'/../resources/views' => resource_path('views/vendors/service'),
        ], 'spondonit');

        $this->commands([
            MigrateStatusCommand::class,
        ]);
    }
}

