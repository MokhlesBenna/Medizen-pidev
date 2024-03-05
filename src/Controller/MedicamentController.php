<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;


#[Route('/medicament')]
class MedicamentController extends AbstractController
{
  
  
    #[Route('/', name: 'app_medicament_index', methods: ['GET'])]
    public function index(Request $request, MedicamentRepository $medicamentRepository, PaginatorInterface $paginator): Response
    {
        $medicaments = $medicamentRepository->findAll();
    
       
        $pagination = $paginator->paginate(
            $medicaments, 
            $request->query->getInt('page', 1), 
            6
        );
    
        return $this->render('medicament/listmedicament.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/all', name: 'app_medicament_all', methods: ['GET'])]
    public function indexx(MedicamentRepository $medicamentRepository): Response
    {
        $medicaments = $medicamentRepository->findAll();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medicaments,
        ]);

    }
    #[Route('/new', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);
    
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                // Handle file upload
                $this->handleFileUpload($medicament);
    
                // Persist the entity
                $entityManager->persist($medicament);
    
                // Flush changes to the database
                $entityManager->flush();
                
    
                return $this->redirectToRoute('app_medicament_all', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            // Log the error
            $this->get('logger')->error('Error creating new Medicament: ' . $e->getMessage());
    
            // Optionally, dump the exception for debugging
            dump($e);
    
            // Handle the error (e.g., show a user-friendly message)
            $this->addFlash('error', 'An error occurred while creating a new Medicament.');
    
            return $this->render('medicament/new.html.twig', [
                'medicament' => $medicament,
                'form' => $form->createView(),
            ]);
        }
    
        return $this->render('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }
    public function generateQrCodeAction(Medicament $medicament)
    {
        // Create a QrCode instance
        $qrCode = new QrCode($medicament->getName());
    
        // Set additional options if needed
        $qrCode->setSize(200);
    
        // Generate the QR code as PNG data
        $qrCodeData = $qrCode();
    
        // Create the response with PNG content type
        $response = new Response($qrCodeData, Response::HTTP_OK, [
            'Content-Type' => 'image/png'
        ]);
    
        return $response;
    }

private function handleFileUpload(Medicament $medicament): void
{
    /** @var UploadedFile|null $imageFile */
    $imageFile = $medicament->getImageFile();

    if ($imageFile) {
        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

        // Move the file to the directory where images are stored
        try {
            $imageFile->move(
                $this->getParameter('medicament_images_directory'), // Configured in services.yaml
                $fileName
            );
        } catch (FileException $e) {
            // Log the error
            $this->get('logger')->error('Error uploading image: ' . $e->getMessage());

            // Optionally, dump the exception for debugging
            dump($e);

            // Handle the error (e.g., show a user-friendly message)
            $this->addFlash('error', 'An error occurred while uploading the image.');

            return;
        }

        // Update the 'image' property to store the file name
        $medicament->setImage($fileName);
    }
}


    #[Route('/{id}', name: 'app_medicament_show', methods: ['GET'])]
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_medicament_delete', methods: ['GET', 'DELETE'])]
    public function deleteMedicament(int $id, MedicamentRepository $medicamentRepository, EntityManagerInterface $entityManager): Response
    {
        $medicament = $medicamentRepository->find($id);
    
        if (!$medicament) {
            throw $this->createNotFoundException('Medicament not found');
        }
    
        $entityManager->remove($medicament);
        $entityManager->flush();
    
        // Add a flash message or handle the response as needed
    
        return $this->redirectToRoute('app_medicament_all');
    }
    



    
#[Route('/{id}/details', name: 'medicament_details', methods: ['GET'], requirements: ['id' => '\d+'])]
public function medicamentDetails(int $id, MedicamentRepository $medicamentRepository): Response
{
    $medicament = $medicamentRepository->find($id);

    if (!$medicament) {
        throw $this->createNotFoundException('Medicament not found');
    }

    // Example: Add chatbot logic here
    $chatbotMessages = $this->getChatbotMessages($medicament);

    return $this->render('medicament/details.html.twig', [
        'medicament' => $medicament,
        'chatbotMessages' => $chatbotMessages,
    ]);
}

private function getChatbotMessages(Medicament $medicament): array
{
    
    return [
        'Hello! How can I assist you with the medicament ' . $medicament->getName() . '?',
        'Do you have any specific questions about this medicament?'. $medicament->getName() . '?',

    ];
}

    #[Route('/sell/{id}/{quantity}', name: 'sell_medicament')]
    public function sellMedicament(
        int $id,
        int $quantity,
        Request $request,
        MedicamentRepository $medicamentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Find the medicament by ID
        $medicament = $medicamentRepository->find($id);
    
        // Check if the medicament exists
        if (!$medicament) {
            throw $this->createNotFoundException('Medicament not found');
        }
    
        // Validate the quantity
        if (!is_int($quantity) || $quantity <= 0) {
            $this->addFlash('danger', 'Invalid quantity.');
            return $this->redirectToRoute('medicament_details', ['id' => $medicament->getId()]);
        }
    
        if ($medicament->getQuantity() >= $quantity) {
            $medicament->setQuantity($medicament->getQuantity() - $quantity);
    
            $commande = new Commande();
            $commande->setQuantityOrdered($quantity);
            $commande->setDateOrdered(new \DateTime());
    
            $medicament->getQuantity() > 0 ? $commande->setStatus('in_stock') : $commande->setStatus('out_of_stock');
    
            $totalPrice = $medicament->getPrice() * $quantity;
            $commande->setTotalprice($totalPrice);
    
            $commande->addMedicamentList($medicament);
    
            // Persist both Medicament and Commande
            $entityManager->persist($medicament);
            $entityManager->persist($commande);
    
            // Flush changes to the database
            $entityManager->flush();
    
            // Render the confirmation template
            return $this->redirectToRoute('checkout', [
                'id' => $medicament->getId(),
                'stripeSK' => 'sk_test_51OqoS0G1DR5SKi86xYIFdz8d62mBHF1uQNV29VnQUECiSI44HVq1Y15yYmXLyc2jZq4DVZIUaX9gRCSp7DDWFCcL00aDAFVNpO', // Replace with your actual Stripe secret key
            ]);
        } else {
            $this->addFlash('danger', 'Not enough quantity available.');
            return $this->redirectToRoute('medicament_details', ['id' => $medicament->getId()]);
        }
    }
    
    

}
