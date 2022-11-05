<?php

namespace Spatie\WelcomeNotification\Tests;

use Spatie\TestTime\TestTime;
use Spatie\WelcomeNotification\Tests\Models\User;
use Spatie\WelcomeNotification\WelcomeNotification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Pest\Laravel\get;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    $this->user = User::create([
        'email' => 'test@example.com',
        'name' => 'test',
    ]);

    $this->welcomeNotification = (new WelcomeNotification(now()->addDay()));
    $this->welcomeNotification->toMail($this->user);
});

it('can show the welcome form', function () {
    get($this->welcomeNotification->showWelcomeFormUrl)
        ->assertSuccessful()
        ->assertViewIs('welcomeNotification::welcome');
});

it('will show the invalid link view when the link is invalid', function () {
    withoutExceptionHandling();

    $invalidWelcomeUrl = $this->welcomeNotification->showWelcomeFormUrl . 'blabla';

    $this->get($invalidWelcomeUrl)
        ->assertSuccessful()
        ->assertViewIs('welcomeNotification::invalidWelcomeLink');
})->throws(HttpException::class);

it('can set the initial password', function () {
    $password = 'my-new-password';

    savePassword($password);

    $this->assertAuthenticatedAs($this->user);
});

it('can login with the new password', function () {
    $password = 'my-new-password';

    savePassword($password);

    expect(auth()->validate([
        'email' => $this->user->email,
        'password' => $password,
    ]))->toBeTrue();

    expect(auth()->validate([
        'email' => $this->user->email,
        'password' => 'invalid password',
    ]))->toBeFalse();
});

test('after being used the welcome url is not valid anymore', function () {
    withExceptionHandling();

    savePassword('my-new-password');

    get($this->welcomeNotification->showWelcomeFormUrl)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('the welcome link will expire after the given point in time', function () {
    withExceptionHandling();

    TestTime::freeze();

    $welcomeNotification = (new WelcomeNotification(now()->addMinute()));
    $welcomeNotification->toMail($this->user);

    TestTime::addSeconds(59);
    get($this->welcomeNotification->showWelcomeFormUrl)->assertSuccessful();

    TestTime::addSecond();
    get($this->welcomeNotification->showWelcomeFormUrl)->assertStatus(Response::HTTP_FORBIDDEN);
});
