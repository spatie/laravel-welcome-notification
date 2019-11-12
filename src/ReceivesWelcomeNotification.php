<?php

namespace Spatie\WelcomeNotification;

trait ReceivesWelcomeNotification
{
    public function sendWelcomeNotifcation()
    {
        $this->notify(new WelcomeNotification());
    }
}
