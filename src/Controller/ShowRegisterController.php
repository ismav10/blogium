<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ShowRegisterController extends AbstractController
{
    /**
     * Shows the form to register.
     *
     * @return Response The user register form.
     */
    public function showRegister() : Response
    {
        return $this->render('register/register.html.twig');
    }
}