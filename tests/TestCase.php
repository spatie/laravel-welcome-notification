<?php

namespace Spatie\WelcomeNotification\Tests;

use AddWelcomeValidUntilFieldToUsersTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\WelcomeNotification\WelcomeController;
use Spatie\WelcomeNotification\WelcomeNotificationServiceProvider;
use Spatie\WelcomeNotification\WelcomesNewUsers;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Model::unguard();

        $this
            ->setUpRoutes()
            ->migrateDatabase();

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

        config()->set('auth.providers.users.model', User::class);
        config()->set('app.key', 'base64:CmNWRD9Yia6R0YVuFal7MUuE32Iqzk2whpEeknTSexc=');
        config()->set('mail.driver', 'log');
    }

    protected function setUpRoutes()
    {
        Route::group(['middleware' => ['web', WelcomesNewUsers::class]], function () {
            Route::get('welcome/{user}', ['\\'.WelcomeController::class, 'showWelcomeForm'])->name('welcome');
            Route::post('welcome/{user}', ['\\'.WelcomeController::class, 'savePassword']);
        });

        return $this;
    }

    protected function migrateDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('name');
            $table->string('password')->nullable();
            $table->timestamps();
        });

        include_once __DIR__.'/../database/migrations/add_welcome_valid_until_field_to_users_table.php.stub';
        (new AddWelcomeValidUntilFieldToUsersTable())->up();

        return $this;
    }
}
