<?php

namespace App\Application\BlogPost;

use App\Domain\BlogPost\Repository\BlogPostRepositoryInterface;

class SearchLastBlogPosts
{
    private BlogPostRepositoryInterface $blogPostRepository;

    public function __construct(BlogPostRepositoryInterface $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }
    
    /**
     * Searchs the last blogs of the platform.
     */
    public function searchLastBlogs(int $limit) : array
    {
        return $this->blogPostRepository->findBy(
                [],
                ['created' => 'DESC'],
                $limit
            );
    }
}