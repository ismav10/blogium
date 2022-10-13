<?php

namespace App\Tests\Unit\Application\Shared\DataGenerator;

use App\Domain\BlogPost\BlogPost;
use App\Domain\User\User;
use App\Application\Shared\DataGenerator\FakerFacade;
use App\Application\Shared\DataGenerator\JsonPlaceholderDataGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function PHPUnit\Framework\throwException;

class JsonPlaceholderDataGeneratorTest extends TestCase
{
    private const API_POSTS_URL = 'https://jsonplaceholder.typicode.com/posts';

    private const API_USERS_URL = 'https://jsonplaceholder.typicode.com/users';

    protected function setUp() : void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->faker = $this->createMock(FakerFacade::class);
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->httpException = $this->createMock(HttpException::class);
        $this->response = $this->createMock(ResponseInterface::class);

        $this->generator = new JsonPlaceholderDataGenerator($this->entityManager, $this->client, $this->faker);

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

        $this->fakerResults = [
            'password',
            'slug',
            $this->date,
            'images/foo.png',
        ];

        $this->userApiResults = [
            [
                'username' => 'username',
                'name' => 'name',
            ]
        ];

        $this->blogApiResults = [
            [
                'title' => 'sentence',
                'body' => 'sample text',
                'userId' => 1   
            ]
        ];
    }

    /**
     * Tests generate method when there is a connection error with the API.
     */
    public function testGenerateWhenThereIsAConnectionError()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', self::API_USERS_URL)
            ->will(throwException($this->httpException));

        $this->expectException(\Exception::class);

        $this->generator->generate();
    }

    /**
     * Tests generate method when is successful.
     */
    public function testGenerateWhenSuccessful()
    {
        $this->client->expects($this->exactly(2))
            ->method('request')
            ->withConsecutive(
                [$this->equalTo('GET'), $this->equalTo(self::API_USERS_URL)],
                [$this->equalTo('GET'), $this->equalTo(self::API_POSTS_URL)],
            )
            ->willReturnOnConsecutiveCalls(...[$this->response, $this->response]);

        $this->response->expects($this->exactly(2))
            ->method('toArray')
            ->willReturnOnConsecutiveCalls(...[$this->userApiResults, $this->blogApiResults]);
        
        $this->faker->expects($this->exactly(4))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(...$this->fakerResults);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->withConsecutive(
                [$this->equalTo($this->user)],
                [$this->equalTo($this->blogPost)]
            );

        $this->generator->generate();
    }
}