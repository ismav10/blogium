<?php

namespace App\Application\Shared\DataGenerator;

abstract class DataGenerator 
{
    private const INTEGRITY_CONSTRAINT_VIOLATION_CODE = 1062;

    /**
     * An array of images with the photos that can be used as media for the blog posts.
     * Can be improved getting these routes with the finder component.
     * Doesn't make sense in a project with real media.
     */
    protected const IMAGES = [
        'images/postphoto1.png',
        'images/postphoto2.png',
        'images/postphoto3.png',
        'images/postphoto4.png',
        'images/postphoto5.png',
        'images/postphoto6.png',
        'images/postphoto7.png',
    ];

    /**
     * The randomly generated users.
     */
    protected array $users;

    /**
     * The randomly generated blog posts.
     */
    protected array $blogPosts;

    /**
     * An instance of the object that passes calls to the third party faker library.
     */
    protected $faker;

    public function __construct(FakerFacade $faker)
    {
        $this->users = [];
        $this->blogPosts = [];
        $this->faker = $faker;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $this->generateUsersAndBlogPosts();
        $this->generateRelationsAndPersist();
        $this->flush();
    }

    /**
     * Flushes the persisted entities to the database.
     * 
     * @throws \Exception Throws an exception if there was any problem persisting to the database.
     */
    private function flush()
    {
        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            if ($e->getCode() === self::INTEGRITY_CONSTRAINT_VIOLATION_CODE) {
                throw new \Exception('There was a constraint violation with the generated data');
            }

            throw $e;
        }
    }

    /**
     * Randomly generates users and blog posts.
     */
    abstract protected function generateUsersAndBlogPosts();

    /**
     * Randomly generates the relationships between the posts and the authors and calls to persist.
     */
    abstract protected function generateRelationsAndPersist();
}