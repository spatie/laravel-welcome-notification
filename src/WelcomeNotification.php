<?php

namespace Spatie\WelcomeNotification;

use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class WelcomeNotification extends Notification
{
    /** @var \Illuminate\Foundation\Auth\User */
    public $user;

    /** @var string */
    public $showWelcomeFormUrl;

    /** @var \Closure|null */
    public static $toMailCallback;

    /** @var \Carbon\Carbon */
    public $validUntil;

    public function __construct(CarbonInterface $validUntil)
    {
        $this->validUntil = $validUntil;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $this->initializeNotificationProperties($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return $this->buildWelcomeNotificationMessage();
    }

    protected function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('Welcome'))
            ->line(Lang::get('You are receiving this email because an account was created for you.'))
            ->action(Lang::get('Set initial password'), $this->showWelcomeFormUrl)
            ->line(Lang::get('This welcome link will expire in :count minutes.', ['count' => $this->validUntil->diffInRealMinutes()]));
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }

    protected function initializeNotificationProperties(User $user)
    {
        $this->user = $user;

        $this->user->welcome_valid_until = $this->validUntil;
        $this->user->save();

        $this->showWelcomeFormUrl = URL::temporarySignedRoute(
            'welcome',
            $this->validUntil,
            ['user' => $user->welcomeNotificationKeyValue()]
        );
    }
}
