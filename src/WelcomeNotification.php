<?php

namespace Spatie\WelcomeMail;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class WelcomeNotification extends Notification
{
    /** @var \Illuminate\Foundation\Auth\User */
    public $user;

    /** @var string */
    public $token;

    /** @var string */
    public $showWelcomeFormUrl;

    /** @var \Closure|null */
    public static $toMailCallback;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $this->initializeNotificationProperties($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildWelcomeMailMessage();
    }

    protected function buildWelcomeMailMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Welcome'))
            ->line(Lang::get('You are receiving this email an account for you was created.'))
            ->action(Lang::get('Set initial password'), $this->showWelcomeFormUrl)
            ->line(Lang::get('This welcome link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]));
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }

    protected function initializeNotificationProperties(User $user)
    {
        $this->user = $user;

        $this->token = Password::getRepository()->create($user);

        $this->showWelcomeFormUrl = route('welcome', [$this->user->id, $this->token]);
    }
}
