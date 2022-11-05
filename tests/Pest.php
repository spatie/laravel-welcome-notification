<?php

use function Pest\Laravel\post;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(Spatie\WelcomeNotification\Tests\TestCase::class)->in('.');

function savePassword(string $password): void
{
    post(test()->welcomeNotification->showWelcomeFormUrl, [
        'email' => test()->user->email,
        'password' => $password,
        'password_confirmation' => $password,
    ])
        ->assertRedirect('/home');
}
