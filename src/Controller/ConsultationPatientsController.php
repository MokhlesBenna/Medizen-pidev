<?php

namespace App\Controller;

use App\Entity\ConsultationPatient;
use App\Form\ConsultationPatientType;
use App\Repository\ConsultationPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/consultation')]
class ConsultationPatientsController extends AbstractController
{
    #[Route('/', name: 'app_consultation_patients_index', methods: ['GET'])]
    public function index(ConsultationPatientRepository $consultationPatientRepository, Request $request, PaginatorInterface $paginator): Response
{
    $query = $consultationPatientRepository->createQueryBuilder('c')->getQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), 2
    );

    return $this->render('consultation_patients/index.html.twig', [
        'consultation_patients' => $pagination,
    ]);
}
#[Route('/new', name: 'app_consultation_patients_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $consultationPatient = new ConsultationPatient();
    $form = $this->createForm(ConsultationPatientType::class, $consultationPatient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($consultationPatient);
        $entityManager->flush();

        return $this->redirectToRoute('app_consultation_patients_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('consultation_patients/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_consultation_patients_show', methods: ['GET'])]
    public function show(ConsultationPatient $consultationPatient): Response
    {
        return $this->render('consultation_patients/show.html.twig', [
            'consultation_patient' => $consultationPatient,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_patients_delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, ConsultationPatient $consultationPatient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $consultationPatient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultationPatient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_patients_index');
    }

    #[Route('/{id}/edit', name: 'app_consultation_patients_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConsultationPatient $consultationPatient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationPatientType::class, $consultationPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_patients_index');
        }

        return $this->renderForm('consultation_patients/edit.html.twig', [
            'consultation_patient' => $consultationPatient,
            'form' => $form,
        ]);
    }

    #[Route('/search-ajax', name: 'consultation_patient_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, ConsultationPatientRepository $consultationPatientRepository): Response
    {
        $searchQuery = $request->query->get('search');

        $consultationPatients = $consultationPatientRepository->searchByName($searchQuery);

        // Vous pouvez formater les données comme vous le souhaitez avant de les renvoyer
        $formattedResults = [];

        foreach ($consultationPatients as $consultationPatient) {
            $formattedResults[] = [
                'id' => $consultationPatient->getId(),
                'name' => $consultationPatient->getName(),
                'surname' => $consultationPatient->getSurname(),
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        return new JsonResponse($formattedResults);
    }
}
