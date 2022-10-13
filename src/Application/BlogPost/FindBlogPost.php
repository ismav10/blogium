<?php

namespace App\Application\BlogPost;

use App\Domain\BlogPost\Repository\BlogPostRepositoryInterface;

class FindBlogPost
{
    private BlogPostRepositoryInterface $blogPostRepository;

    public function __construct(BlogPostRepositoryInterface $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }
    
    /**
     * Find a blog post by id.
     * 
     * @param int $id The id of the blog post to find.
     */
    public function findBlogPost(int $id)
    {
        return $this->blogPostRepository->find($id);
    }
}