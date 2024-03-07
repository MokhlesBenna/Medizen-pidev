<?php

namespace App\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Repository\DocteurRepository;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;    
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TwilioService;
use SebastianBergmann\Environment\Console;
use Psr\Log\LoggerInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{


#[Route('/', name: 'app_reservation_index', methods: ['GET'])]
public function StatusFilterationPagination(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator): Response
{
    $status = $request->query->get('status');
    
    if ($status) {
        $reservations = $reservationRepository->findBy(['status' => $status]);
    } else {
        $reservations = $reservationRepository->findAll();
    }

    $pagination = $paginator->paginate(
        $reservations,
        $request->query->getInt('page', 1), 
        2 
    );

    return $this->render('reservation/index.html.twig', [
        'reservations' => $pagination,
        'status' => $status,
    ]);
}
#[Route('/edit/{id}', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Form submission handling
        $entityManager->flush(); // Update the entity

        $this->addFlash('success', 'Reservation updated successfully.');
        return $this->redirectToRoute('app_reservation_index');
    }

    return $this->render('reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form->createView(),
    ]);
}

 

    
    #[Route('/admin', name: 'app_reservation_admin', methods: ['GET'])]
    public function listeReservations(Request $request, PaginatorInterface $paginator, ReservationRepository $reservationRepository): Response
    {
        $status = $request->query->get('status');

    
    if ($status) {
        $reservations = $reservationRepository->findBy(['status' => $status]);
    } else {
        $reservations = $reservationRepository->findAll();
    }

    
    $pagination = $paginator->paginate(
        $reservations,
        $request->query->getInt('page', 1), 
        2 
    );

   
    return $this->render('reservation/admin.html.twig', [
        'reservations' => $pagination,
        'status' => $status,
    ]);
    }
        
    #[Route('show/admin/{id}', name: 'app_reservation_show')]
    public function showAdmin(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
}

    #[Route('/admin/reject/{id}', name: 'reject_reservation', methods: ['POST'])]
    public function rejectReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatus('Rejected');
        $entityManager->persist($reservation);
        $entityManager->flush();
        $this->addFlash('success', 'Rendez-vous rejeté avec succès.');
        return new RedirectResponse($this->generateUrl('app_reservation_admin'));
    }


    #[Route('/admin/accept/{id}', name: 'accept_reservation', methods: ['POST'])]
    public function activateReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatus('Accepted');
        $entityManager->persist($reservation);
        $entityManager->flush();
        $this->addFlash('success', 'Réservation acceptée!');
        return new RedirectResponse($this->generateUrl('app_reservation_admin'));
    }
    
    

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, DocteurRepository $docteurRepository): Response
{
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reservationDate = $form->get('reservation_date')->getData();
        $heure = $reservationDate->format('H');

        if ($heure < 9 || $heure > 17) {
            $this->addFlash('error', 'L\'heure de réservation doit être comprise entre 9h et 17h.');
            return $this->redirectToRoute('app_reservation_new');
        }

        if ($reservation->setReservationDate($reservationDate)) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            $smsController = new \App\Controller\SMSController();
            $d=$reservation->getReservationDate()->format('Y-m-d');
            $smsController->index('reservation done with success , name: '.$reservation->getName().' surname :'.$reservation->getSurname().' date réservation: '.$d);

            $this->addFlash('success', 'Votre rendez-vous a été réservé avec succès.');
            return $this->redirectToRoute('app_reservation_new');
        } else {
            $this->addFlash('error', 'La date de réservation doit être aujourd\'hui ou dans le futur.');
            return $this->redirectToRoute('app_reservation_new');
        }
    }

    return $this->render('reservation/new.html.twig', [
        'form' => $form->createView(),
        'docteurs' => $docteurRepository->findAll(),    
    ]);
}

    #[Route('/{id}/delete', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('show/{id}', name: 'app_reservation_show')]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
}


#[Route('/page', name: 'page', methods: ['GET'])]
public function pagination(Request $request, ReservationRepository $repository ): Response
{
    $query = $repository->createQueryBuilder('e');

    $paginator = new Paginator($query);
    
    $page = $request->query->getInt('page', 1);
    $perPage = 10; 
    $paginator
        ->getQuery()
        ->setFirstResult(($page - 1) * $perPage)
        ->setMaxResults($perPage);

   
    return $this->render('reservation/index.html.twig', [
        'reservations' => $repository->findAll(),
        'paginator' => $paginator,
    ]);
}

#[Route('/reservation/search-ajax', name: 'reservation_search_ajax', methods: ['GET'])]
public function searchAjax(Request $request, ReservationRepository $reservationRepository): JsonResponse
{
    $searchQuery = $request->query->get('search');

    $reservations = $reservationRepository->searchByName($searchQuery);

   
    $formattedResults = [];

    foreach ($reservations as $reservation) {
        $formattedResults[] = [
            'id' => $reservation->getId(),
            'name' => $reservation->getName(),
        ];
    }

    return new JsonResponse($formattedResults);
}

#[Route('/cal', name: 'app_cal', methods: ['GET'])]
public function cal(ReservationRepository $appointmentRepository)
{
    $events = $appointmentRepository->findAll();

    $rdvs = [];

    foreach ($events as $event) {
        $rdvs[] = [
            'id' => $event->getId(),
            'start' => $event->getReservationDate()->format('Y-m-d H:i:s'),
            'end' => $event->getReservationDate()->format('Y-m-d H:i:s'),
            'title' => $event->getStatus(),
        ];

        
    }

 

    $data = json_encode($rdvs);
   
    echo '<script>console.log(' . json_encode($data) . ');</script>';
    return $this->render('reservation/showCalendar.html.twig', compact('data'));
}

}