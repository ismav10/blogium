<?php

namespace App\Controller;

use App\Service\User\UserCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    public function register(Request $request, UserCreator $userCreator) : Response
    {
        $userData = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'fullname' => $request->request->get('fullname')
        ];
        $errors = $userCreator->create($userData);

        if (count($errors) > 0) {
            return $this->render('register/register.html.twig', [
                'errors' => $errors
            ]);
        }

        return $this->render('register/register.html.twig', [
            'messages' => [ 'Correctly registered' ]
        ]);
    }

    public function showRegister() : Response
    {
        return $this->render('register/register.html.twig');
    }
}