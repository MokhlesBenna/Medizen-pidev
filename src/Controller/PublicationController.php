<?php

namespace App\Controller;
use App\Entity\Topic;
use App\Entity\like;
use App\Entity\User;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
            
        ]);
    }



    #[Route('/new/{topicId}', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $topicId): Response
    {
        $topic = $entityManager->getRepository(Topic::class)->find($topicId);
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setTopic($topic); // Assurez-vous que la publication est associée au bon topic
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_details', ['id' => $topic->getId()]);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
            'topic' => $topic
        ]);
    }

    


    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication, EntityManagerInterface $entityManager): Response
    { $id_user = 1;
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id_user]);
        $existingLike = $entityManager->getRepository(Like::class)->findOneBy(['user' => $user, 'id_publication' => $publication]);
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'like' => $existingLike,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
            'button_label1' => 'Modifier',
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/delete/{id}', name: 'app_publication_delete', methods: ['GET', 'DELETE'])]
    public function deletepublication(int $id, publicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
        $publication = $publicationRepository->find($id);

        if (!$publication) {
            throw $this->createNotFoundException('publication not found');
        }

        $entityManager->remove($publication);
        $entityManager->flush();

        // Add a flash message or handle the response as needed

        return $this->redirectToRoute('app_publication_index');
    }
    
   

   
}