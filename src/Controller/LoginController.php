<?php

namespace App\Controller;

use App\Entity\CleanUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError(); 

        return $this->render(
            'login/index.html.twig',
            [
                'error' => $error
            ]
        );

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError(); 

        return $this->render(
            'login/index.html.twig',
            [
                'error' => $error
            ]
        );

    }
}
