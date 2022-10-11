<?php

namespace App\Service\DataGenerator;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonPlaceholderDataGenerator extends DataGenerator
{
    private const API_POSTS_URL = 'https://jsonplaceholder.typicode.com/posts';
    private const API_USERS_URL = 'https://jsonplaceholder.typicode.com/users';
    protected EntityManagerInterface $entityManager;
    private HttpClientInterface $client;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $client, FakerFacade $faker)
    {
        parent::__construct($faker);
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateUsersAndBlogPosts()
    {
        $usersData = $this->makeRequestAndTransformToArray(self::API_USERS_URL);
        $blogsData = $this->makeRequestAndTransformToArray(self::API_POSTS_URL);

        foreach ($usersData as $userData) {
            $this->users[] = $this->generateUser($userData);
        }

        foreach ($blogsData as $blogData) {
            $this->blogPosts[] = $this->generateBlogPost($blogData);
        }
    }

    /**
     * Makes a request to the api and transform the results to an array.
     * 
     * @throws \Exception If there was a problem with the connection with the api.
     */
    private function makeRequestAndTransformToArray(string $url)
    {
        try {
            $response = $this->client->request(
                'GET',
                $url
            );

            return $response->toArray();
        } catch (HttpExceptionInterface $e) {
            throw new \Exception('There was an issue with the http request to the API');
        }
    }

    /**
     * Randomly generates an user.
     */
    private function generateUser(array $userData) : User
    {
        $user = new User();

        $user->setUsername($userData['username']);
        $user->setPassword($this->faker->execute('password'));
        $user->setFullname($userData['name']);

        return $user;
    }

    /**
     * Randomly generates a blog post.
     */
    private function generateBlogPost(array $blogData) : BlogPost
    {
        $blogPost = new BlogPost();

        $blogPost->setTitle($blogData['title']);
        $blogPost->setBody($blogData['body']);
        $blogPost->setSlug($this->faker->execute('slug'));
        $blogPost->setCreated($this->faker->execute('dateTimeBetween', '-20 days', '+20 days'));
        $blogPost->setMedia($this->faker->execute('randomElement', self::IMAGES));

        $author = $this->users[$blogData['userId'] - 1];

        $blogPost->setAuthor($author);

        return $blogPost;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateRelationsAndPersist()
    {
        foreach ($this->users as $user) {
            $this->entityManager->persist($user);
        }

        foreach ($this->blogPosts as $blogPost) {
            $this->entityManager->persist($blogPost);
        }
    }
}