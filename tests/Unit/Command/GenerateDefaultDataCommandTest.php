<?php

namespace App\Tests\Unit\Command;

use App\Command\GenerateDefaultDataCommand;
use App\Service\DataGenerator\DataGeneratorFactory;
use App\Service\DataGenerator\FakerDataGenerator;
use App\Service\DataGenerator\JsonPlaceholderDataGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateDefaultDataCommandTest extends KernelTestCase
{
    protected function setUp() : void 
    {
        $this->fakerDataGenerator = $this->createMock(FakerDataGenerator::class);
        $this->apiDataGenerator = $this->createMock(JsonPlaceholderDataGenerator::class);
        $this->dataGeneratorFactory = $this->createMock(DataGeneratorFactory::class);

        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);

        $this->command = new GenerateDefaultDataCommand($this->fakerDataGenerator, $this->dataGeneratorFactory);
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * Tests execute command with option fake.
     */
    public function testExecuteWhenModeFake()
    {
        $this->dataGeneratorFactory->expects($this->once())
            ->method('getDataGenerator')
            ->with('fake', 10)
            ->willReturn($this->fakerDataGenerator);

        $this->fakerDataGenerator->expects($this->once())
            ->method('generate');

        $this->commandTester->execute([ 
            '-m' => 'fake',
            '-l' => 10 
        ]);

        $this->commandTester->assertCommandIsSuccessful();
    }

    /**
     * Tests execute command with option api.
     */
    public function testExecuteWhenModeApi()
    {
        $this->dataGeneratorFactory->expects($this->once())
            ->method('getDataGenerator')
            ->with('api', 10)
            ->willReturn($this->apiDataGenerator);

        $this->apiDataGenerator->expects($this->once())
            ->method('generate');

        $this->commandTester->execute([ 
            '-m' => 'api',
            '-l' => 10 
        ]);

        $this->commandTester->assertCommandIsSuccessful();
    }

    /**
     * Tests execute command when the mode is not valid.
     */
    public function testExecuteWhenInvalidMode()
    {
        $this->dataGeneratorFactory->expects($this->never())
            ->method('getDataGenerator');

        $this->commandTester->execute([ 
            '-m' => 'json',
            '-l' => 10 
        ]);
    }

    /**
     * Tests execute command when the mode is valid but the data generation invalid.
     */
    public function testExecuteWhenValidModeButInvalidResult()
    {
        $this->dataGeneratorFactory->expects($this->once())
            ->method('getDataGenerator')
            ->with('fake', 10)
            ->willReturn($this->apiDataGenerator);

        $this->apiDataGenerator->expects($this->once())
            ->method('generate')
            ->will($this->throwException(new \Exception()));

        $this->commandTester->execute([ 
            '-m' => 'fake',
            '-l' => 10 
        ]);
    }
}