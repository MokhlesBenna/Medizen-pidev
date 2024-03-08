<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\DepartementRepository;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etablissement')]
class EtablissementController extends AbstractController
{
    #[Route('/', name: 'app_etablissement_index', methods: ['GET'])]
    public function index(EtablissementRepository $etablissementRepository,Request $request): Response
    {
        $tasks = $etablissementRepository->findAll();
        //search
        $searchQuery = $request->query->get('search');

           // Si une recherche par name est effectuée, filtrer les etablissements en conséquence
           if ($searchQuery) {
            $tasks = $etablissementRepository->findByName($searchQuery);}
            
        

        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $tasks
        ]);
    }

    #[Route('/statistique', name: 'app_etablissement_stat', methods: ['GET'])]
    public function indexStat(EtablissementRepository $etablissementRepository): Response
    {
        $tasks = $etablissementRepository->findAll();
        $counts = $etablissementRepository->countEstablishmentsWithSameLocation();
        
        $labels = array_keys($counts);
        $dataYes = array_values($counts);
        return $this->render('etablissement/stat.html.twig', [
            'etablissements' => $tasks,
            'labels' => json_encode($labels),  // Convert PHP array to JSON for use in JavaScript
            'dataYes' => json_encode($dataYes)
        ]);
    }

    #[Route('/admin', name: 'app_etablissement_indexadmin', methods: ['GET'])]
    public function indexadmin(EtablissementRepository $etablissementRepository): Response
    {
        return $this->render('etablissement/indexback.html.twig', [
            'etablissements' => $etablissementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etablissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etablissement);
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/new.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etablissement_show', methods: ['GET'])]
    public function show(Etablissement $etablissement,DepartementRepository $depR ): Response
    {
        
        
        return $this->render('etablissement/show.html.twig', [
            'etablissement' => $etablissement,
            'departements' => $depR->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etablissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etablissement_delete', methods: ['POST'])]
    public function delete(Request $request, Etablissement $etablissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etablissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etablissement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/etablissement/map', name: 'show_map')]
    public function showEtablissementMap(): Response
    {
        // Retrieve offer data from the database, including latitude and longitude
        $etablissements = $this->getDoctrine()->getRepository(Etablissement::class)->findAll();

        return $this->render('etablissement/map.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }


}
