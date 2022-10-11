<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCreator
{
    private UserRepository $userRepository;

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->user = new User();
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    /**
     * Creates an user in the database based on an associative array of data.
     * 
     * @param array $userData The array with the user data.
     * 
     * @return ConstraintViolationList
     */
    public function create(array $userData) : ConstraintViolationList
    {
        $this->generateUser($userData);

        $errors = $this->validator->validate($this->user);

        if (count($errors) > 0) {
            return $errors;
        }

        $this->persistUser();

        return $errors;
    }

    /**
     * Generates an user entity based on the request data.
     */
    private function generateUser(array $userData) : void
    {
        /**
         * This loops over the properties sended by the controller in a generic way
         * calling the corresponding set method based on the key and throwing and exception
         * if the method doesn't exists in the entity.
         */
        foreach (array_keys($userData) as $key) {
            $method = 'set' . ucfirst($key);
            $this->user->{$method}($userData[$key]);
        }
    }

    /**
     * Persists and flush the user entity.
     */
    private function persistUser() : void
    {
        $this->userRepository->save($this->user, true);
    }
}