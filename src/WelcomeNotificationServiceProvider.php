<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WelcomeNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::macro('handleWelcome', function (string $url = '') {
            Route::prefix($url)->group(function () {
                Route::get('welcome/{userId}/{token}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
                Route::post('welcome', [WelcomeController::class, 'savePassword'])->name('welcome.save-password');
            });
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'WelcomeNotification');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/WelcomeNotification'),
        ], 'views');
    }
}
