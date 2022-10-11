<?php

namespace App\Service\DataGenerator;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataGeneratorFactory 
{
    private EntityManagerInterface $entityManager;

    private HttpClientInterface $client;

    private FakerFacade $faker;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $client, FakerFacade $faker)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
        $this->faker = $faker;
    }

    /**
     * Returns a data generator based on the mode passed.
     * 
     * @param string $mode The mode of the data generator.
     * @param int $limit The limit of data to generate.
     * 
     * @throws LogicException If the arguments were not validated before call to this factory method.
     * 
     * @return DataGenerator The data generator to get the data from.
     */
    public function getDataGenerator(string $mode, ?int $limit = 100) 
    {
        switch ($mode) {
            case 'fake':
                return new FakerDataGenerator($this->entityManager, $this->faker, $limit);

            case 'api':
                return new JsonPlaceholderDataGenerator($this->entityManager, $this->client, $this->faker);

            default:
                throw new LogicException(sprintf('The arguments must be checked before get into %s', get_class($this)));
        }
    }
}