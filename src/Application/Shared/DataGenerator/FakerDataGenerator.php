<?php

namespace App\Application\Shared\DataGenerator;

use App\Domain\BlogPost\BlogPost;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;

class FakerDataGenerator extends DataGenerator
{
    private const MAX_NUMBER_OF_PARAGRAPHS = 5000;

    /**
     * The limit of users and blogs to generate.
     */
    private ?int $limit;

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, FakerFacade $faker, ?int $limit = 100,) 
    {
        parent::__construct($faker);
        $this->entityManager = $entityManager;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateUsersAndBlogPosts()
    {
        for ($i = 0;$i < $this->limit; $i++) {
            $this->users[] = $this->generateUser();
            $this->blogPosts[] = $this->generateBlogPost();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateRelationsAndPersist()
    {
        for ($i = 0;$i < $this->limit; $i++) {
            $this->generateAuthorForBlog($this->blogPosts[$i]);

            $this->entityManager->persist($this->blogPosts[$i]);
            $this->entityManager->persist($this->users[$i]);
        }
    }

    /**
     * Generates a random user.
     */
    private function generateUser() : User
    {
        $user = new User();

        $user->setUsername($this->faker->execute('username'));
        $user->setPassword($this->faker->execute('password'));
        $user->setFullName($this->faker->execute('name'));

        return $user;
    }

    /**
     * Generates a random post.
     */
    private function generateBlogPost() : BlogPost
    {
        $blogPost = new BlogPost();

        $blogPost->setTitle($this->faker->execute('sentence'));
        $blogPost->setBody($this->faker->execute('text', self::MAX_NUMBER_OF_PARAGRAPHS));
        $blogPost->setSlug($this->faker->execute('slug'));
        $blogPost->setCreated($this->faker->execute('dateTimeBetween', '-20 days', '+20 days'));
        $blogPost->setMedia($this->faker->execute('randomElement', self::IMAGES));

        return $blogPost;
    }

    /**
     * Uses a random user as a blog's author.
     */
    private function generateAuthorForBlog(BlogPost $blog)
    {
        $blog->setAuthor($this->faker->execute('randomElement', $this->users));
    }
}