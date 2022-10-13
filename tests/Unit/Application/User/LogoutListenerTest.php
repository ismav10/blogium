<?php

namespace App\Tests\Unit\Listeners;

use App\Application\User\LogoutListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListenerTest extends TestCase
{
    protected function setUp() : void
    {
        $this->response = $this->createMock(Response::class);
        $this->event = $this->createMock(LogoutEvent::class);
        $this->headers = $this->createMock(ResponseHeaderBag::class);

        $this->response->headers = $this->headers;

        $this->listener = new LogoutListener();
    }

    /**
     * Tests that the listener clears the bearer cookie used for api authentication.
     */
    public function testOnLogoutSuccess()
    {
        $this->event->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);

        $this->headers->expects($this->once())
            ->method('clearCookie')
            ->with('Bearer');

        $this->listener->onLogoutSuccess($this->event);
    }
}