<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_index_front')]
    public function index(): Response
    {
        
        //dd($this->getUser()->getRoles()[0]);
        if ($this->getUser()->getRoles()[0]=="ROLE_USER")
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        else if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN"){
            return $this->render('home/index_admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        }
    }
     #[Route('/admin', name: 'app_index_admin')]
    public function index_admin(): Response
    {
        return $this->render('home/index_admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
