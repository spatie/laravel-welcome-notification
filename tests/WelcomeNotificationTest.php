<?php

namespace Spatie\WelcomeNotification\Tests;

use Illuminate\Support\Facades\Notification;
use Spatie\WelcomeNotification\Tests\Models\User;
use Spatie\WelcomeNotification\WelcomeNotification;

class WelcomeNotificationTest extends TestCase
{
    /** @var \Spatie\WelcomeNotification\Tests\Models\User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'test@example.com',
            'name' => 'test',
        ]);
    }

    /** @test */
    public function it_can_send_the_welcome_notification()
    {
        Notification::fake();

        $this->user->notify(new WelcomeNotification());

        Notification::assertSentTo($this->user, WelcomeNotification::class);
    }
}
