# Send a welcome notification to new users

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-welcome-notification.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-welcome-notification)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-welcome-notification/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-welcome-notification.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-welcome-notification)

Using this package you can send a `WelcomeNotification` to a new user of your app. The notification contains a secure link to a screen where the user can set an initial password.

```php
$expiresAt = now()->addDay();

$user->sendWelcomeNotification($expiresAt);
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-welcome-notification.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-welcome-notification)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-welcome-notification
```

### Migrating the database

You must publish the migrations provided by this package by executing this command:

```bash
php artisan vendor:publish --provider="Spatie\WelcomeNotification\WelcomeNotificationServiceProvider" --tag="migrations"
```

Next, you must migrate your database.

```bash
php artisan migrate
```

### Preparing the user model

You must apply the `\Spatie\WelcomeNotification\ReceivesWelcomeNotification` trait to your `User` model.

### Preparing the WelcomeController

Next you'll need to create a controller of your own that will extend `Spatie\WelcomeNotification\WelcomeController`. This controller will be used to show the welcome form and to save the password set by a user.

```php
namespace App\Http\Controllers\Auth;

use Spatie\WelcomeNotification\WelcomeController as BaseWelcomeController;

class MyWelcomeController extends BaseWelcomeController
{
}
```

### Registering the routes

You'll have to register these routes:

```php
use Spatie\WelcomeNotification\WelcomesNewUsers;
use App\Http\Controllers\Auth\MyWelcomeController;

Route::group(['middleware' => ['web', WelcomesNewUsers::class,]], function () {
    Route::get('welcome/{user}', [MyWelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welcome/{user}', [MyWelcomeController::class, 'savePassword']);
});
```

### Preparing the welcome form view

The `welcome` view that ships with the package, will be rendered when somebody clicks the welcome link in the welcome notification mail. You should style this view yourself. You can publish the views with this command:

```bash
php artisan vendor:publish --provider="Spatie\WelcomeNotification\WelcomeNotificationServiceProvider" --tag="views"
```

## Usage

Here's how you can send a welcome notification to a user that you just created.

```php
$expiresAt = now()->addDay();

$user->sendWelcomeNotification($expiresAt);
```

## Handling successful requests

After the a user has successfully set a new password the `sendPasswordSavedResponse` of the `WelcomeController` will get called.

```php
use Symfony\Component\HttpFoundation\Response;

class MyWelcomeController extends BaseWelcomeController
{
    public function sendPasswordSavedResponse(): Response

    {
        return redirect()->route('home');
    }
}
```

## Customizing the notification

By default the `WelcomeNotification` will send a mail. If you wish to customize the mail you can extend `WelcomeNotification` and override the `buildWelcomeNotificationMessage` method.

```php
use Illuminate\Notifications\Messages\MailMessage;

class MyCustomWelcomeNotification extends WelcomeNotification
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to my app')
            ->action(Lang::get('Set initial password'), $this->showWelcomeFormUrl);
    }
}
```

To use the custom notification you must add a method called `sendWelcomeNotification` to your `User` model.

```php
public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
{
    $this->notify(new MyCustomWelcomeNotification($validUntil));
}
```

## Validating extra fields

The default welcome form that ships with this package only asks for a password. You can add more fields to the form by [publishing the view](https://github.com/spatie/laravel-welcome-notification#preparing-the-welcome-form-view) and adding more fields to it.

To validate new fields you can override the `rules` function in your own `WelcomeController`. Here's an example where we want to validate an extra field named `job_title`.

```php
class MyWelcomeController extends BaseWelcomeController
{
    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'job_title' => 'required',
        ];
    }
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
