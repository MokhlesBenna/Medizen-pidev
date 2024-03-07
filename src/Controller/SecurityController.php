<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils , UserRepository $userRepo ): Response
    {
        // if ($this->getUser()->getRoles()== ['ROLE_ADMIN',] && $this->getUser()== ['ROLE_USER']) {
        //      return $this->redirectToRoute('app_index_admin');
        //  }
        
        // else if ($this->getUser()== ['ROLE_USER']) {
        //      return $this->redirectToRoute('app_index_front');
        //  }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();
        //dd($userRepo->find($user)->isBlocked());

        if ($user)
        $isBlocked = $userRepo->find($user->getId())->isBlocked();
        else $isBlocked = false;
        
        //dd($this->getUser()->getRoles()[0]);

            //dd($user);
            

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error , 'isBlocked' => $isBlocked]);
    }

   #[Route(path: '/logout', name: 'app_logout')]
public function logout(UrlGeneratorInterface $urlGenerator): RedirectResponse
{
    // Perform the logout logic if needed

    // Redirect to the front page
    return new RedirectResponse($urlGenerator->generate('app_index_admin'));
}
}
