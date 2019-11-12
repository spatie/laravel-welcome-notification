<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WelcomeNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'welcomeNotification');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/welcomeNotification'),
        ], 'views');
    }
}
