<?php

namespace App\Tests\Unit\Application\User;

use App\Application\User\CreateUser;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserTest extends TestCase
{
    /**
     * Configures the user creator tests.
     */
    protected function setUp() : void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->errors = $this->createMock(ConstraintViolationList::class);

        $this->userCreator = new CreateUser($this->validator, $this->userRepository);
    }

    /**
     * Tests that the user creator doesn't call method save on the repository when there is a validation error.
     */
    public function testCreateUserWhenEmptyUsername()
    {
        $userData = [
            'fullname' => 'Foo Bar Mix',
            'password' => 'admin1234'
        ];

        $user = new User();
        $user->setFullname('Foo Bar Mix');
        $user->setPassword('admin1234');

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($user)
            ->willReturn($this->errors);

        $this->errors->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->userCreator->create($userData);
    }

    /**
     * Tests the user creator when all is successful and the entity is persisted.
     */
    public function testCreateUserWhenSuccessful()
    {
        $userData = [
            'fullname' => 'Foo Bar Mix',
            'password' => 'admin1234',
            'username' => 'happypath'
        ];

        $user = new User();
        $user->setFullname('Foo Bar Mix');
        $user->setPassword('admin1234');
        $user->setUsername('happypath');

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn($this->errors);

        $this->errors->expects($this->once())
            ->method('count')
            ->willReturn(0);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user, true);

        $this->userCreator->create($userData);
    }
}