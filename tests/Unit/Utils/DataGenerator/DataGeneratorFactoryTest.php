<?php

namespace App\Tests\Unit\Utils\DataGenerator;

use App\Utils\DataGenerator\DataGeneratorFactory;
use App\Utils\DataGenerator\FakerDataGenerator;
use App\Utils\DataGenerator\FakerFacade;
use App\Utils\DataGenerator\JsonPlaceholderDataGenerator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataGeneratorFactoryTest extends TestCase
{
    protected function setUp() : void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->faker = $this->createMock(FakerFacade::class);

        $this->fakerDataGenerator = new FakerDataGenerator($this->entityManager, $this->faker, 50);
        $this->apiDataGenerator = new JsonPlaceholderDataGenerator($this->entityManager, $this->client, $this->faker);

        $this->factory = new DataGeneratorFactory($this->entityManager, $this->client, $this->faker);
    }

    /**
     * Tests method getDataGenerator when mode is fake.
     */
    public function testGetDataGeneratorWhenModeIsFake()
    {
        $this->assertEquals($this->fakerDataGenerator, $this->factory->getDataGenerator('fake', 50));
    }

    /**
     * Tests method getDataGenerator when mode is api.
     */
    public function testGetDataGeneratorWhenModeIsApi()
    {
        $this->assertEquals($this->apiDataGenerator, $this->factory->getDataGenerator('api'));
    }

    /**
     * Tests method getDataGenerator when the mode is invalid.
     */
    public function testGetDataGeneratorWhenModeIsInvalid()
    {
        $this->expectException(LogicException::class);

        $this->factory->getDataGenerator('foo');
    }
}