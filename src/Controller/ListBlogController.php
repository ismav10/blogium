<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ListBlogController extends AbstractController
{
    /**
     * Shows a list of the last posts of the platform.
     */
    public function listBlogs(ManagerRegistry $doctrine) : Response
    {
        $posts = $doctrine->getRepository(BlogPost::class)
            ->findBy(
                [],
                ['created' => 'DESC'],
                10
            );

        return $this->render('blog/list.html.twig', [
            'posts' => $posts
        ]);
    }
}