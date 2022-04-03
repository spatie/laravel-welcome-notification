<?php

namespace Spatie\WelcomeNotification;

use Carbon\Carbon;


trait ReceivesWelcomeNotification
{
    public function useWelcomeNotificationKey(): string
    {
        return $this->{$this->useWelcomeNotificationKeyName()};
    }
    public function useWelcomeNotificationKeyName(): string
    {
        return $this->getKeyName();
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
