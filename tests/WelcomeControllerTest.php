<?php

namespace Spatie\WelcomeNotification\Tests;

use Spatie\TestTime\TestTime;
use Spatie\WelcomeNotification\Tests\Models\User;
use Spatie\WelcomeNotification\WelcomeNotification;
use Symfony\Component\HttpFoundation\Response;

class WelcomeControllerTest extends TestCase
{
    /** @var \Illuminate\Foundation\Auth\User */
    private $user;

    /** @var \Spatie\WelcomeNotification\WelcomeNotification */
    private $welcomeNotification;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'test@example.com',
            'name' => 'test',
        ]);

        $this->welcomeNotification = (new WelcomeNotification(now()->addDay()));
        $this->welcomeNotification->toMail($this->user);
    }

    /** @test */
    public function it_can_show_the_welcome_form()
    {
        $this
            ->get($this->welcomeNotification->showWelcomeFormUrl)
            ->assertSuccessful()
            ->assertViewIs('welcomeNotification::welcome');
    }

    public function it_will_show_the_invalid_link_view_when_the_link_is_invalid()
    {
        $invalidWelcomeUrl = $this->welcomeNotification->showWelcomeFormUrl.'blabla';

        $this
            ->get($invalidWelcomeUrl)
            ->assertSuccessful()
            ->assertViewIs('welcomeNotification::invalidWelcomeLink');
    }

    /** @test */
    public function it_can_set_the_initial_password()
    {
        $password = 'my-new-password';

        $this->savePassword($password);

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function it_can_login_with_the_new_password()
    {
        $password = 'my-new-password';

        $this->savePassword($password);

        $this->assertTrue(auth()->validate([
            'email' => $this->user->email,
            'password' => $password,
        ]));

        $this->assertFalse(auth()->validate([
            'email' => $this->user->email,
            'password' => 'invalid password',
        ]));
    }

    /** @test */
    public function after_being_used_the_welcome_url_is_not_valid_anymore()
    {
        $this->withExceptionHandling();

        $this->savePassword('my-new-password');

        $this
            ->get($this->welcomeNotification->showWelcomeFormUrl)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function the_welcome_link_will_expire_after_the_given_point_in_time()
    {
        $this->withExceptionHandling();

        TestTime::freeze();

        $welcomeNotification = (new WelcomeNotification(now()->addMinute()));
        $welcomeNotification->toMail($this->user);

        TestTime::addSeconds(59);
        $this->get($this->welcomeNotification->showWelcomeFormUrl)->assertSuccessful();

        TestTime::addSecond();
        $this->get($this->welcomeNotification->showWelcomeFormUrl)->assertStatus(Response::HTTP_FORBIDDEN);
    }

    protected function savePassword(string $password): void
    {
        $this
            ->post($this->welcomeNotification->showWelcomeFormUrl, [
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertRedirect('/home');
    }
}
