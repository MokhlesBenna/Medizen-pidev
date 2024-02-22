<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    #[Route('/all', name: 'app_event_indexx', methods: ['GET'])]
    public function indexx(EventRepository $eventRepository): Response
    {
        return $this->render('event/eventshow.html.twig', [
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
    #[Route('/admin/event/{id}/details', name: 'event_details_admin', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function eventDetailsAdmin(int $id, eventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        return $this->render('event/showadmin.html.twig', [
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

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/eventshow.html.twig', [
            'event' => $event,
        ]);
    }
    #[Route('/admin/event/{id}/details', name: 'event_details_admin', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function eventDetails(int $id, eventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        return $this->render('event/details.html.twig', [
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
            throw $this->createNotFoundException('Medicament not found');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        // Add a flash message or handle the response as needed

        return $this->redirectToRoute('app_event_index');
    }
}
