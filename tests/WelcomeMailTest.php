<?php

namespace Spatie\WelcomeMail\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Spatie\WelcomeMail\WelcomeMail;

class WelcomeMailTest extends TestCase
{
    /** @var \Illuminate\Foundation\Auth\User */
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
    public function it_can_send_the_welcome_mail()
    {
        Mail::fake();

        Mail::send(new WelcomeMail($this->user));

        Mail::assertQueued(WelcomeMail::class);
    }

    /** @test */
    public function it_can_render_the_welcome_mail()
    {
        $this->assertIsString((new WelcomeMail($this->user))->render());
    }
}
