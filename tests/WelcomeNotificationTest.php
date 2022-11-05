<?php

use Illuminate\Support\Facades\Notification;
use Spatie\WelcomeNotification\Tests\Models\User;
use Spatie\WelcomeNotification\WelcomeNotification;

beforeEach(function () {
    $this->user = User::create([
        'email' => 'test@example.com',
        'name' => 'test',
    ]);
});

it('can send the welcome notification', function () {
    Notification::fake();

    $this->user->sendWelcomeNotification(now()->addDay());

    Notification::assertSentTo($this->user, WelcomeNotification::class);
});
