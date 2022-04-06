<?php

namespace Spatie\WelcomeNotification;

use Carbon\Carbon;

trait ReceivesWelcomeNotification
{
    public function welcomeNotificationKeyName()
    {
        return $this->getKeyName();
    }

    public function welcomeNotificationKeyValue()
    {
        return $this->{$this->welcomeNotificationKeyName()};
    }

    public function sendWelcomeNotification(Carbon $validUntil)
    {
        $this->notify(new WelcomeNotification($validUntil));
    }

    public function markAsInitialPasswordSet()
    {
        $this->welcome_valid_until = null;
        $this->save();

        return $this;
    }
}
