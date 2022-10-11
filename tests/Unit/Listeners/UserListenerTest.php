<?php

namespace App\Tests\Unit\Listeners;

use App\Entity\User;
use App\Listeners\UserListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListenerTest extends TestCase
{
    protected function setUp() : void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->user = $this->createMock(User::class);

        $this->listener = new UserListener($this->passwordHasher);
    }

    /**
     * Tests the only possible execution flow of the prePersist method.
     */
    public function testPrePersist()
    {
        $this->user->expects($this->once())
            ->method('getPassword')
            ->willReturn('foo');

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->user, 'foo')
            ->willReturn('$2y$13$HSn8SsLWXIaOcMQtaWbCcurbekh1CdK47yrE/G0pmccs9c2y8AvTy');

        $this->user->expects($this->once())
            ->method('setPassword')
            ->with('$2y$13$HSn8SsLWXIaOcMQtaWbCcurbekh1CdK47yrE/G0pmccs9c2y8AvTy');

        $this->listener->prePersist($this->user);
    }
}