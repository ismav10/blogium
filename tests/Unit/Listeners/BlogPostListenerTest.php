<?php

namespace App\Tests\Unit\Listeners;

use App\Domain\BlogPost\BlogPost;
use App\Domain\User\User;
use App\Listeners\BlogPostListener;
use App\Utils\DataGenerator\FakerFacade;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BlogPostListenerTest extends TestCase
{
    protected function setUp() : void
    {
        $this->security = $this->createMock(Security::class);
        $this->user = $this->createMock(User::class);
        $this->blogPost = $this->createMock(BlogPost::class);
        $this->faker = $this->createMock(FakerFacade::class);

        // The returning type of the Slugger mock is causing problems. 
        // Is because of that that there is a concrete instance here
        $this->slugger = new AsciiSlugger();

        $this->listener = new BlogPostListener($this->security, $this->faker, $this->slugger);
    }

    /**
     * Tests method prePersist when there is an user authenticated.
     */
    public function testPrePersistWhenAnUserIsAuthenticated()
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($this->user);

        $this->blogPost->expects($this->once())
            ->method('setAuthor')
            ->with($this->user);

        $this->blogPost->expects($this->once())
            ->method('getTitle')
            ->willReturn('Foo bar');

        $this->blogPost->expects($this->once())
            ->method('setSlug')
            ->with('foo-bar');

        $this->blogPost->expects($this->once())
            ->method('setCreated');

        $this->listener->prePersist($this->blogPost);
    }

    /**
     * Tests method prePersist when there is no user authenticated.
     */
    public function testPrePersistWhenNotUserIsAuthenticated()
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->blogPost->expects($this->never())
            ->method('setAuthor');

        $this->blogPost->expects($this->once())
            ->method('getTitle')
            ->willReturn('Foo bar');

        $this->blogPost->expects($this->once())
            ->method('setSlug')
            ->with('foo-bar');

        $this->blogPost->expects($this->once())
            ->method('setCreated');

        $this->listener->prePersist($this->blogPost);
    }
}