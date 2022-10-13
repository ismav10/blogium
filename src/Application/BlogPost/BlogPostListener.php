<?php

namespace App\Application\BlogPost;

use App\Application\Shared\DataGenerator\FakerFacade;
use App\Domain\BlogPost\BlogPost;
use DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogPostListener
{
    private Security $security;

    private FakerFacade $faker;

    private SluggerInterface $slugger;

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

    public function __construct(Security $security, FakerFacade $faker, SluggerInterface $slugger)
    {
        $this->security = $security;
        $this->slugger = $slugger;
        $this->faker = $faker;
    }

    /**
     * Generates all the necessary data for the blog post that is not passed in the request.
     */
    public function prePersist(BlogPost $blogPost)
    {
        $user = $this->security->getUser();

        if ($user) {
            $blogPost->setAuthor($user);
        }

        $slug = $this->slugger->slug($blogPost->getTitle());

        $blogPost->setSlug(strtolower($slug));
        $blogPost->setMedia($this->faker->execute('randomElement', self::IMAGES));
        $blogPost->setCreated(new DateTime());
    }
}