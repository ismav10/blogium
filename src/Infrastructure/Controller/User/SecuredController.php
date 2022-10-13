<?php

namespace App\Infrastructure\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SecuredController extends AbstractController
{
    public function index() : Response
    {
        return $this->render('spa.html.twig');
    }
}