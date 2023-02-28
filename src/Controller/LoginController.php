<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->redirectToRoute('app_homepage_index', [
            'last_username' => $lastUsername,
            'error'=> $error,
        ], Response::HTTP_SEE_OTHER);

    }
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): Response
    {
        //This controller can be blank because is never going to be called. The logout is managed in security.yaml
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
