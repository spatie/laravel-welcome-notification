<?php

namespace Spatie\WelcomeMail;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WelcomeMailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::macro('welcomeMail', function (string $url = '') {
            Route::prefix($url)->group(function () {
                Route::get('welcome/{userId}/{token}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
                Route::post('welcome', [WelcomeController::class, 'savePassword'])->name('welcome.save-password');
            });
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'welcomeMail');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/welcomeMail'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/lang' => "{$this->app['path.lang']}/vendor/welcomeMail",
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'welcomeMail');
    }
}
