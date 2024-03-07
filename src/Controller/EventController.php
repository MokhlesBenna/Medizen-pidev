<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entity,EventRepository $eventRepository,PaginatorInterface $paginator,Security $security, Request $request): Response
    {
       
        $user = $security->getUser();
        $event = $eventRepository->findAll();
        $pagination = $paginator->paginate(

            $event,
            $request->query->getInt('page', 1), // Current page
            4   // Items per page
        );
        return $this->render('event/index.html.twig', [
            'events' => $pagination,
        ]);
    }

    #[Route('/all', name: 'app_event_indexx', methods: ['GET'])]
public function indexx(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Récupérer tous les événements depuis le repository
    $events = $eventRepository->findAll();

    // Paginer les résultats
    $pagination = $paginator->paginate(
        $events,
        $request->query->getInt('page', 1), // Page actuelle
        4   // Éléments par page
    );

    // Rendre la vue avec les événements paginés
    return $this->render('event/eventshow.html.twig', [
        'events' => $pagination,
    ]);
}


    #[Route('/a', name: 'app_event_detail', methods: ['GET'])]
    public function indexxsh(EventRepository $eventRepository): Response
    {
        return $this->render('event/show.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/home', name: 'app_event_home', methods: ['GET'])]
    public function home(EventRepository $eventRepository): Response
    {
        return $this->render('event/home.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'événement existe déjà
            $existingEvent = $this->getDoctrine()->getRepository(Event::class)->findOneBy([
                'titre' => $event->gettitre(),
                'lieu' => $event->getlieu(),
                // Ajouter d'autres critères si nécessaire
            ]);
    
            if ($existingEvent) {
                // Ajouter un message flash pour informer que l'événement existe déjà
                $this->addFlash('error_' . $event->getId(), 'Cet événement existe déjà.');
            } else {
                // Traitement du téléchargement de l'image
                $image = $form->get('image')->getData();
                $this->handleImageUpload($image, $event, $slugger);
    
                // Persist et flush uniquement si l'événement n'existe pas déjà
                $entityManager->persist($event);
                $entityManager->flush();
    
                // Ajouter un message flash pour informer du succès de l'ajout
                $this->addFlash('success_' . $event->getId(), 'Événement ajouté avec succès.');
                
                return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    private function handleImageUpload($image, Event $event, SluggerInterface $slugger)
    {
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            // Move the file to the directory where your images are stored
            try {
                $image->move(
                    $this->getParameter('event_directory'), // defined in services.yaml or config/services.yaml
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception if something goes wrong during file upload
                throw new FileException('Error uploading the image');
            }

            // Update the 'image' property of the Event entity
            $event->setImage($newFilename);
        }
    }

    #[Route('/admin/event/{id}/details', name: 'event_details_admin', methods: ['GET'], requirements: ['id' => '\d+'])]
public function eventDetails(int $id, EventRepository $eventRepository): Response
{
    $event = $eventRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    return $this->render('event/details.html.twig', [
        'event' => $event,
    ]);
}


    
    #[Route('/admin/event', name: 'app_event_index_admin')]
    public function indexAdmin1(eventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('event/admin.html.twig', [
            'events' => $events,
        ]);
    }


    #[Route('/admin')]

    public function indexAdmin(): Response
    {

        return $this->render(
            'event/admin.html.twig'
        );
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    


#[Route('/delete/{id}', name: 'app_event_delete', methods: ['GET', 'DELETE'])]
public function deleteevent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
{
    $event = $eventRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    $entityManager->remove($event);
    $entityManager->flush();

    // Ajoutez un message flash ou gérez la réponse selon les besoins

    return $this->redirectToRoute('app_event_index');
}
// EventController.php

public function deleteExpiredEvents(EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
{
    $expiredEvents = $eventRepository->findExpiredEvents();

    foreach ($expiredEvents as $event) {
        $entityManager->remove($event);
    }

    $entityManager->flush();

    return new Response('Expired events deleted successfully');
}

#[Route(name: 'OrderBydateDebut')]
public function orderByDateDebut(EventRepository $repo)
{
    $eventsOrderedBydateDebut = $repo->findBydateDebutOrdered();

    return $this->render('event/index.html.twig', [
        'event' => $eventsOrderedBydateDebut,
    ]);

}
}
