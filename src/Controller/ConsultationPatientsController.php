<?php

namespace App\Controller;

use App\Entity\ConsultationPatient;
use App\Form\ConsultationPatientType;
use App\Repository\ConsultationPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consultation')]
class ConsultationPatientsController extends AbstractController
{
    #[Route('/', name: 'app_consultation_patients_index', methods: ['GET'])]
    public function index(ConsultationPatientRepository $consultationPatientRepository): Response
    {
        return $this->render('consultation_patients/index.html.twig', [
            'consultation_patients' => $consultationPatientRepository->findAll(),
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

        return $this->renderForm('consultation_patients/new.html.twig', [
            'consultation_patient' => $consultationPatient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_patients_show', methods: ['GET'])]
    public function show(ConsultationPatient $consultationPatient): Response
    {
        return $this->render('consultation_patients/show.html.twig', [
            'consultation_patient' => $consultationPatient,
        ]);
    }
    
    #[Route('/{id}', name: 'app_consultation_patients_delete', methods: ['POST'])]
    public function delete(Request $request, ConsultationPatient $consultationPatient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultationPatient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultationPatient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_patients_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_consultation_patients_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConsultationPatient $consultationPatient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationPatientType::class, $consultationPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_patients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation_patients/edit.html.twig', [
            'consultation_patient' => $consultationPatient,
            'form' => $form,
        ]);
    }

   
}
