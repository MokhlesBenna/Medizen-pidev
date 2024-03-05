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


#[Route('/reservation')]
class ReservationController extends AbstractController
{

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $query = $reservationRepository->createQueryBuilder('r')->getQuery();

    $pagination=$reservationRepository->findAll();
    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), 
        2 
    );

    
    return $this->render('reservation/index.html.twig', [
        'reservations' => $pagination,
    ]);
        
    }
    #[Route('/admin', name: 'app_reservation_admin', methods: ['GET'])]
public function listeReservations(Request $request, PaginatorInterface $paginator, ReservationRepository $reservationRepository): Response
{
    
    $reservations = $reservationRepository->findAll();

    
    $pagination = $paginator->paginate(
        $reservations,
        $request->query->getInt('page', 1),
        10 
    );

    
    return $this->render('reservation/admin.html.twig', [
        'reservations' => $pagination,
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

        // Vérifie si l'heure de réservation est entre 9h et 17h
        if ($heure < 9 || $heure > 17) {
            $this->addFlash('error', 'L\'heure de réservation doit être comprise entre 9h et 17h.');
            return $this->redirectToRoute('app_reservation_new');
        }

        // Si la date de réservation est dans le futur
        if ($reservation->setReservationDate($reservationDate)) {
            $entityManager->persist($reservation);
            $entityManager->flush();
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

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    dump($reservation->getAddress());

    return $this->renderForm('reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
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

}