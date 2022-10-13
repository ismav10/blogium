<?php

namespace App\Infrastructure\Controller\BlogPost;

use App\Application\BlogPost\SearchLastBlogPosts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ListBlogController extends AbstractController
{
    /**
     * Shows a list of the last posts of the platform.
     */
    public function listBlogs(SearchLastBlogPosts $searcher) : Response
    {
        return $this->render('blog/list.html.twig', [
            'posts' => $searcher->searchLastBlogs(10)
        ]);
    }
}