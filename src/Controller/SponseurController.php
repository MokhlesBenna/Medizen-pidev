<?php

namespace App\Controller;

use App\Entity\Sponseur;
use App\Form\SponseurType;
use App\Repository\SponseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
#[Route('/sponseur')]
class SponseurController extends AbstractController
{
    #[Route('/', name: 'app_sponseur_index', methods: ['GET'])]
    public function index(SponseurRepository $sponseurRepository): Response
    {
        return $this->render('sponseur/index.html.twig', [
            'sponseurs' => $sponseurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sponseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $sponseur = new Sponseur();
        $form = $this->createForm(SponseurType::class, $sponseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logo = $form->get('logo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($logo) {
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $logo->move(
                        $this->getParameter('sponseur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $sponseur->setLogo($newFilename);
            }
            $entityManager->persist($sponseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_sponseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponseur/new.html.twig', [
            'sponseur' => $sponseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponseur_show', methods: ['GET'])]
    public function show(Sponseur $sponseur): Response
    {
        return $this->render('sponseur/show.html.twig', [
            'sponseur' => $sponseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponseur $sponseur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SponseurType::class, $sponseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sponseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponseur/edit.html.twig', [
            'sponseur' => $sponseur,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_sponseur_delete', methods: ['GET', 'DELETE'])]
public function deletesponseur(int $id, sponseurRepository $sponseurRepository, EntityManagerInterface $entityManager): Response
{
    $sponseur = $sponseurRepository->find($id);

    if (!$sponseur) {
        throw $this->createNotFoundException('sponseur not found');
    }

    $entityManager->remove($sponseur);
    $entityManager->flush();

    // Ajoutez un message flash ou gérez la réponse selon les besoins

    return $this->redirectToRoute('app_sponseur_index');
}
}
