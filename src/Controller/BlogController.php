<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class BlogController extends AbstractController
{
    public function list(ManagerRegistry $doctrine) : Response
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

    public function show(ManagerRegistry $doctrine, int $id) : Response
    {
        $post = $doctrine->getRepository(BlogPost::class)
            ->find($id);

        if (!$post) {
            throw new ResourceNotFoundException();
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]);
    }
}