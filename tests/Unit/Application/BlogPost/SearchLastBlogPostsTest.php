<?php

namespace App\Tests\Unit\Application\BlogPost;

use App\Application\BlogPost\SearchLastBlogPosts;
use App\Domain\BlogPost\Repository\BlogPostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SearchLastBlogPostsTest extends TestCase
{
    protected function setUp() : void
    {
        $this->blogRepository = $this->createMock(BlogPostRepositoryInterface::class);

        $this->searchLastBlogs = new SearchLastBlogPosts($this->blogRepository);
    }

    /**
     * Tests the search of the last blogs.
     */
    public function testSearchLastBlogs()
    {
        $this->blogRepository->expects($this->once())
            ->method('findBy')
            ->with(
                [],
                ['created' => 'DESC'],
                10
            )
            ->willReturn([]);

        $this->searchLastBlogs->searchLastBlogs(10);
    }
}