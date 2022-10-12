<?php

namespace App\Listeners;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class ApiAuthenticationSuccessListener
{
    /**
     * Adds a httpOnly cookie to the response headers to save 
     * a JWT when the authentication is successful.
     * 
     * @param AuthenticationSuccessEvent
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $response = $event->getResponse();
        $data = $event->getData();

        $token = $data['token'];

        $cookie = new Cookie('Bearer', $token, httpOnly: true);
        $response->headers->setCookie($cookie);
    }
}