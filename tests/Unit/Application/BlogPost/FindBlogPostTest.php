<?php

namespace App\Tests\Unit\Application\BlogPost;

use App\Application\BlogPost\FindBlogPost;
use App\Domain\BlogPost\BlogPost;
use App\Domain\BlogPost\Repository\BlogPostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindBlogPostTest extends TestCase
{
    protected function setUp() : void
    {
        $this->blogRepository = $this->createMock(BlogPostRepositoryInterface::class);
        $this->blogPost = $this->createMock(BlogPost::class);

        $this->findBlogPost = new FindBlogPost($this->blogRepository);
    }

    /**
     * Tests the search of the last blogs.
     */
    public function testSearchLastBlogs()
    {
        $this->blogRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->blogPost);

        $this->findBlogPost->findBlogPost(1);
    }
}