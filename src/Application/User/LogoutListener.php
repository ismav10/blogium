<?php

namespace App\Application\User;

use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    /**
     * Removes the cookie to log into the api.
     */
    public function onLogoutSuccess(LogoutEvent $event) : void
    {
        $response = $event->getResponse();

        $response->headers->clearCookie('Bearer');
    }
}