<?php

namespace Spatie\WelcomeNotification\Tests;

use Illuminate\Foundation\Auth\User;
use Spatie\WelcomeNotification\WelcomeController;
use Spatie\WelcomeNotification\WelcomeNotification;

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

        $this->welcomeNotification = (new WelcomeNotification());

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
           'password' =>  $password,
        ]));

        $this->assertFalse(auth()->validate([
            'email' => $this->user->email,
            'password' =>  'invalid password',
        ]));
    }

    /** @test */
    public function after_being_used_the_welcome_url_is_not_valid_anymore()
    {
        $this->savePassword('my-new-password');

        $this
            ->get($this->welcomeNotification->showWelcomeFormUrl)
            ->assertSuccessful()
            ->assertViewIs('welcomeNotification::invalidWelcomeLink');
    }

    protected function savePassword(string $password): void
    {
        $this
            ->post(action([WelcomeController::class, 'savePassword']), [
                'token' => $this->welcomeNotification->token,
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertRedirect('/home');
    }
}
