<?php

namespace Spatie\WelcomeNotification\Tests;

use CreateAuthTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\WelcomeNotification\WelcomeController;
use Spatie\WelcomeNotification\WelcomeNotificationServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Model::unguard();

        $this->setUpRoutes();

        $this->withoutExceptionHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            WelcomeNotificationServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/database/migrations/create_auth_tables.php.stub';
        (new CreateAuthTables())->up();

        config()->set('auth.providers.users.model', User::class);
        config()->set('app.key', 'base64:CmNWRD9Yia6R0YVuFal7MUuE32Iqzk2whpEeknTSexc=');
        config()->set('mail.driver', 'log');
    }

    protected function setUpRoutes(): void
    {
        Route::group(['middleware' => ['web']], function () {
            Route::get('welcome/{userId}/{token}', ['\\'.WelcomeController::class, 'showWelcomeForm'])->name('welcome');
            Route::post('welcome', ['\\'.WelcomeController::class, 'savePassword'])->name('welcome.save-password');
        });
    }
}
