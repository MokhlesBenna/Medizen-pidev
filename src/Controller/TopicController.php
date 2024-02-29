<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/topic')]
class TopicController extends AbstractController
{
    #[Route('/', name: 'app_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    #[Route('/home', name: 'app_topic_home', methods: ['GET'])]
    public function home(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/home.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }
    #[Route('/all/{id}', name: 'app_topic_details', methods: ['GET'])]
    public function details(Topic $topic): Response
    {
        return $this->render('topic/details.html.twig', [
            'topic' => $topic,
        ]);
    }
    #[Route('/admin')]

    public function indexAdmin(): Response
    {

        return $this->render(
            'topic/admin.html.twig'
        );
    }

    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_topic_show', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
            'button_label' => 'Modifier',
        ]);
    }

    #[Route('/{id}', name: 'app_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->request->get('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
   
    
    #[Route('/delete/{id}', name: 'app_topic_delete', methods: ['GET', 'DELETE'])]
    public function deletetopic(int $id, topicRepository $topicRepository, EntityManagerInterface $entityManager): Response
    {
        $topic = $topicRepository->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('pas de topic');
        }

        $entityManager->remove($topic);
        $entityManager->flush();

        // Add a flash message or handle the response as needed

        return $this->redirectToRoute('app_topic_index');
    }

    
}
