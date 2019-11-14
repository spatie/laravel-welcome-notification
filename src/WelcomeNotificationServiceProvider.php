<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Support\ServiceProvider;

class WelcomeNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'welcomeNotification');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/welcomeNotification'),
        ], 'views');

        if (! class_exists('AddWelcomeValidUntilFieldToUsersTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/add_welcome_valid_until_field_to_users_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_add_welcome_valid_until_field_to_users_table.php'),
            ], 'migrations');
        }
    }
}
