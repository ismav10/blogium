<?php

namespace App\Tests\Unit\Service\DataGenerator;

use App\Service\DataGenerator\FakerFacade;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FakerFacadeTest extends TestCase
{
    protected function setUp() : void 
    {
        $this->facade = new FakerFacade();
    }

    /**
     * Tests method execute when requesting a text.
     */
    public function testExecuteWhenText()
    {
        $this->assertIsString($this->facade->execute('text'));
    }

    /**
     * Tests method execute when requesting a number.
     */
    public function testExecuteWhenNumber()
    {
        $this->assertIsNumeric($this->facade->execute('numberBetween'));
    }

    /**
     * Tests method execute when the operation doesn't exist in the faker library.
     */
    public function testExecuteWhenWrongOperation()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->facade->execute('nonExistentOperation');
    }
}