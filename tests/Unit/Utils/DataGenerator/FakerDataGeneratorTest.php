<?php

namespace App\Tests\Unit\Utils\DataGenerator;

use App\Domain\BlogPost\BlogPost;
use App\Domain\User\User;
use App\Utils\DataGenerator\FakerDataGenerator;
use App\Utils\DataGenerator\FakerFacade;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FakerDataGeneratorTest extends TestCase
{
    private const INTEGRITY_CONSTRAINT_VIOLATION_CODE = 1062;

    protected function setUp() : void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->faker = $this->createMock(FakerFacade::class);

        $this->generator = new FakerDataGenerator($this->entityManager, $this->faker, 1);

        $this->date = new DateTime();

        $this->user = new User();

        $this->user->setUsername('username');
        $this->user->setPassword('password');
        $this->user->setFullname('name');

        $this->blogPost = new BlogPost();

        $this->blogPost->setTitle('sentence');
        $this->blogPost->setBody('sample text');
        $this->blogPost->setSlug('slug');
        $this->blogPost->setCreated($this->date);
        $this->blogPost->setMedia('images/foo.png');
        $this->blogPost->setAuthor($this->user);

        $this->results = [
            'username',
            'password',
            'name',
            'sentence',
            'sample text',
            'slug',
            $this->date,
            'images/foo.png',
            $this->user
        ];
    }

    /**
     * Tests the generate method when there is an integrity constraint violation.
     */
    public function testGenerateWhenThereIsAConstraintViolation()
    {
        $this->faker->expects($this->exactly(9))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(...$this->results);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->withConsecutive(
                [$this->equalTo($this->blogPost)], 
                [$this->equalTo($this->user)]
            );
        
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->will($this->throwException(new \Exception('', self::INTEGRITY_CONSTRAINT_VIOLATION_CODE)));

        $this->expectException(\Exception::class);

        $this->generator->generate();
    }

    /**
     * Tests the generate method when there is an integrity constraint violation.
     */
    public function testGenerateWhenThereIsAExceptionFlushing()
    {
        $this->faker->expects($this->exactly(9))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(...$this->results);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->withConsecutive(
                [$this->equalTo($this->blogPost)], 
                [$this->equalTo($this->user)]
            );
        
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->will($this->throwException(new \Exception()));

        $this->expectException(\Exception::class);

        $this->generator->generate();
    }

    /**
     * Tests the generate method when it is successful.
     */
    public function testGenerateWhenSuccessful()
    {
        $this->faker->expects($this->exactly(9))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(...$this->results);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->withConsecutive(
                [$this->equalTo($this->blogPost)], 
                [$this->equalTo($this->user)]
            );

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->generator->generate();
    }
}