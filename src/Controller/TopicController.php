<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Commentaire;
use App\Entity\Like;
use App\Entity\Topic;
use App\Entity\User;
use App\Entity\Publication;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Notification\NewTopicNotification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/topic')]
class TopicController extends AbstractController
{ 
    #[Route('/', name: 'app_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findAllOrderedByCreationDate();

        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/home', name: 'app_topic_home', methods: ['GET'])]
    public function home(TopicRepository $topicRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $topics = $topicRepository->findAll();// Paginer les résultats
    $pagination = $paginator->paginate(
        $topics,
        $request->query->getInt('page', 1), // Page actuelle
        4   // Éléments par page
    );
        return $this->render('topic/home.html.twig', [
            'topics' => $pagination,
        ]);
    }
    #[Route('/all/{id}', name: 'app_topic_details', methods: ['GET'])]
    public function details(Topic $topic, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager,Publication $publication,User $user): Response
    {$id_user = 1;
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id_user]);
        $publications = $publicationRepository->findBy(['topic' => $topic]);
       
        return $this->render('topic/details.html.twig', [
            'topic' => $topic,
            'publications' => $publications,
            'user' => $user,
            
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

    
   

    #[Route('/addaufavoris/{id}', name: 'app_addaufavoris')]
    public function ajouteraufavoris(Topic $topic, SessionInterface $session): Response
    {
        // On récupère les favoris actuels de la session
        $favoris = $session->get("favoris", []);
    
        // Récupérer l'identifiant du topic
        $id = $topic->getId();
    
        // Ajouter le topic aux favoris avec un compteur de 1
        $favoris[$id] = 1;
    
        // Sauvegarder les favoris dans la session
        $session->set("favoris", $favoris);
    
        // Rediriger vers la page des favoris
        return $this->redirectToRoute("app_favoris");
    }
    

#[Route('/favoris', name: 'app_favoris')]
public function affichefavoris(SessionInterface $session, TopicRepository $repository)
{
    $favoris = $session->get("favoris", []);

    // On "fabrique" les données
    $dataFavoris = [];

    foreach($favoris as $id => $quantite){
        $topic = $repository->find($id);
        if ($topic ) { 

        $dataFavoris[] = [
            "topic" => $topic,
        ];
        }
    }

    return $this->render('topic/favoris.html.twig', compact("dataFavoris"));

}
    #[Route('/deletedufavoris/{id}', name: 'app_deletedufavoris')]
    public function deletedufavoris(TopicRepository $repository,$id, SessionInterface $session)
    {
        $topic=new topic();                                 
        $topic = $repository->find($id);                          
        // On récupère le panier actuel
        $favoris = $session->get("favoris", []);
        $id = $topic->getId();

        if(!empty($favoris[$id])){
            unset($favoris[$id]);
        }

        // On sauvegarde dans la session
        $session->set("favoris", $favoris);

        return $this->redirectToRoute("app_favoris");
    }
   #[Route('/{id}/listepub', name: 'admin_publication_list')]
    public function listPublication(int $id, PublicationRepository $publicationRepository): Response
    {
        // Récupérer les publications associées au topic
        $publications = $publicationRepository->findBy(['topic' => $id]);

        // Rendre la vue listepub.html.twig en passant les publications
        return $this->render('topic/listepub.html.twig', [
            'publications' => $publications,
        ]);
    }

    #[Route('/like/{id}', name: 'app_publication_like', methods: ['GET','POST'])]
    public function like(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $id_user = 1;
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id_user]);
        $existingLike = $entityManager->getRepository(Like::class)->findOneBy(['user' => $user, 'id_publication' => $publication]);

    if (!$existingLike) {
        $like = new Like();
        $like->setUser($user);
        $like->setIdPublication($publication);
        $entityManager->persist($like);
    } else {
        $entityManager->remove($existingLike);
    }

    $entityManager->flush();

        return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/comment/{id}', name: 'app_publication_comment', methods: ['GET','POST'])]
public function comment(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
{
    $id_user = 1;  //a changer
    $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id_user]);

    $commentText = $request->request->get('commentText');
    $commentaires = $publication->getCommentaires();

    if ($commentText) {
        $comment = new Commentaire();
        $comment->setDatedecreation(new \DateTime());
        $comment->setIdUser($user);
        $comment->setPublication($publication);
        $comment->setContenu($commentText);
        $entityManager->persist($comment);
        $entityManager->flush();
    }
    return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId(),
    'commentaires' => $commentaires, ], Response::HTTP_SEE_OTHER);
}


    
}
