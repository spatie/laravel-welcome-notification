<?php

namespace Spatie\WelcomeMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var \Illuminate\Foundation\Auth\User */
    public $user;

    /** @var string */
    public $token;

    /** @var string */
    public $showWelcomeFormUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->token = Password::getRepository()->create($user);

        $this->showWelcomeFormUrl = route('welcome', [$this->user->id, $this->token]);
    }

    public function build()
    {
        return $this
            ->to($this->user->email)
            ->subject(trans('welcomeMail::translations.welcome_mail_subject', ['application_name', config('app.name')]))
            ->view('welcomeMail::mails.welcome');
    }
}
