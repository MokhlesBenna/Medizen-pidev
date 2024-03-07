<?php

namespace App\Controller;
use DateTime;
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
    public function participer(int $id, UserRepository $userRepository, SendMailService $mail): Response
    {
        $userId = 1; // à remplacer par id salim session
        $user = $userRepository->findByUserId($userId);
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $currentDateTime = new DateTime();

        if ($currentDateTime >= $event->getDateDebut()) {
            // L'événement a déjà commencé, renvoie une réponse JSON avec un indicateur d'erreur
            return new JsonResponse(['success' => false, 'error' => 'Cet événement a déjà commencé. Vous ne pouvez pas participer.']);
        } else {
            foreach ($user as $user) {
                $event->addUser($user);
            }

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

            // Renvoie une réponse JSON avec un indicateur de succès
            return new JsonResponse(['success' => true]);
        }
    }

    

}
