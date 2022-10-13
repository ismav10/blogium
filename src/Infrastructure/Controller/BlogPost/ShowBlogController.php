<?php

namespace App\Infrastructure\Controller\BlogPost;

use App\Application\BlogPost\FindBlogPost;
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
    public function showBlog(FindBlogPost $finder, int $id) : Response
    {
        $post = $finder->findBlogPost($id);

        if (!$post) {
            throw new ResourceNotFoundException();
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]);
    }
}