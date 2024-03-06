<?php

namespace App\Controller;

use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Event;
use App\Repository\UserRepository;

class ParticipationController extends AbstractController
{
    #[Route('/participation', name: 'app_participation')]
    public function index(): Response
    {
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }
    #[Route('/participer/{id}', name: 'participer_event', methods: ['POST'])]
    public function participer(int $id,UserRepository $userRepository,SendMailService $mail): Response
    {
        // Récupérez l'utilisateur connecté
        
        $userid = 1; // à remplacer par id salim session
        $user= $userRepository->findByUserId($userid);

        // Récupérez l'événement
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        // Ajoutez l'utilisateur à la liste des participants de l'événement
        foreach ($user as $user) {
            $event->addUser($user);
          }

        // Enregistrez les modifications en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();
        $mail->send(
            'espritagri11@gmail.com',
            $user->getEmail(),
            'Confirmation de participation',
            'event',
            ['event'=> 'event']
          );

        // Ajoutez un message flash pour la notification
        $this->addFlash('success', 'Vous avez participé à l\'événement avec succès!');

        // Redirigez l'utilisateur vers la même page ou une autre page si nécessaire
        return $this->redirectToRoute('app_event_indexx');

        
    }
}
