<?php

namespace AlogicProjects\Email2fa;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use AlogicProjects\Email2fa\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'alogicemail2fa');
        $this->loadViewsFrom(__DIR__ . '/../../../laravel/nova/resources/views', 'nova');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__ . '/../config/alogicemail2fa.php' => config_path('alogicemail2fa.php'),
            ], 'alogicemail2fa.config');

            // Publishing the migrations.
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');
        }

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('alogic/email2fa')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/alogicemail2fa.php', 'alogicemail2fa');
    }
}
