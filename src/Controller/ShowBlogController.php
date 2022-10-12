<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ShowBlogController extends AbstractController
{
    /**
     * Shows a concrete blog post based on the id passed.
     * 
     * @throws ResourceNotFoundException if the id doesn't exists.
     */
    public function showBlog(ManagerRegistry $doctrine, int $id) : Response
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