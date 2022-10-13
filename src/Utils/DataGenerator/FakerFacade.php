<?php

namespace App\Utils\DataGenerator;

class FakerFacade
{
    /**
     * An instance of the faker from the faker library.
     */
    private $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param string $method The name of the method to execute on the faker object.
     * @param mixed $arguments The arguments to pass to the method to be executed.
     * 
     * @return mixed The result of the call to the faker object.
     */
    public function execute(string $method, mixed ...$arguments) : mixed
    {
        return $this->faker->{$method}(...$arguments);
    }
}