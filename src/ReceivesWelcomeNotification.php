<?php

namespace Spatie\WelcomeNotification;

use Carbon\Carbon;

trait ReceivesWelcomeNotification
{
    /**
     * Provides the property to be used by WelcomeNotification when generating
     * the temporary signed route
     * @return mixed
     */
    public function useWelcomeNotificationKey()
    {
        return $this->{$this->useWelcomeNotificationKeyName()};
    }

    /**
     * Provides the property key to be used by useWelcomeNotificationKey when
     * not overridden by the model.
     * @return string
     */
    public function useWelcomeNotificationKeyName()
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
