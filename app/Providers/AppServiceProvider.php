<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Http\Middleware\LogFilamentActivity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('log.filament', LogFilamentActivity::class);
        
        // Aplicar a todas las rutas de Filament
        Filament::serving(function () {
            Filament::registerRenderHook(
                'body.start',
                fn () => app('router')->pushMiddlewareToGroup('web', LogFilamentActivity::class),
            );
        });
    }
}




