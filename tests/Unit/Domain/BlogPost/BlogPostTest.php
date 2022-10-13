<?php

namespace App\Tests\Unit\Domain\BlogPost;

use App\Domain\BlogPost\BlogPost;
use App\Domain\User\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class BlogPostTest extends TestCase
{
    protected function setUp() : void
    {
        $this->user = $this->createMock(User::class);

        $this->blogPost = new BlogPost();

        $this->date = new DateTime();
    }

    /**
     * Tests that the entity creation works properly.
     */
    public function testCreateBlogPost()
    {
        $this->blogPost->setTitle('sample title');
        $this->blogPost->setBody('sample body');
        $this->blogPost->setCreated($this->date);
        $this->blogPost->setSlug('sample-title');
        $this->blogPost->setMedia('/path/to/photo');
        $this->blogPost->setAuthor($this->user);

        $this->assertEquals('sample title', $this->blogPost->getTitle());
        $this->assertEquals('sample body', $this->blogPost->getBody());
        $this->assertEquals($this->date, $this->blogPost->getCreated());
        $this->assertEquals('sample-title', $this->blogPost->getSlug());
        $this->assertEquals('/path/to/photo', $this->blogPost->getMedia());
        $this->assertEquals($this->user, $this->blogPost->getAuthor());
    }
}