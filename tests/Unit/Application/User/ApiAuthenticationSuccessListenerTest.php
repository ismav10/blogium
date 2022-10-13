<?php

namespace App\Tests\Unit\Application\User;

use App\Application\User\ApiAuthenticationSuccessListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ApiAuthenticationSuccessListenerTest extends TestCase
{
    protected function setUp() : void
    {
        $this->event = $this->createMock(AuthenticationSuccessEvent::class);
        $this->response = $this->createMock(Response::class);
        $this->response->headers = $this->createMock(ResponseHeaderBag::class);

        $this->listener = new ApiAuthenticationSuccessListener();
    }

    /**
     * Tests that the method authentication success gets the token and sets the cookie.
     */
    public function testOnAuthenticationSuccess()
    {
        $expectedCookie = new Cookie('Bearer', 'JWTToken', httpOnly: true);

        $this->event->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);

        $this->event->expects($this->once())
            ->method('getData')
            ->willReturn([ 'token' => 'JWTToken' ]);

        $this->response->headers->expects($this->once())
            ->method('setCookie')
            ->with($expectedCookie);

        $this->listener->onAuthenticationSuccess($this->event);
    }
}