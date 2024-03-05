<?php

namespace App\Controller;
use App\Entity\Medicament;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException; // Import the exception class
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout($stripeSK, $id): Response
    {
        // Fetch medicament details from your database
        $medicament = $this->getDoctrine()
            ->getRepository(Medicament::class)
            ->find($id);
    
        if (!$medicament) {
            // Handle the case where the medicament with the given ID is not found
            throw $this->createNotFoundException('The medicament does not exist');
        }
    
        // Set your Stripe API key when constructing the StripeClient instance
        $stripe = new \Stripe\StripeClient(['api_key' => $stripeSK]);
    
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $medicament->getName(),
                         #   'images' =>   $medicament->getImageFile(),

                        ],
                        'unit_amount'  => (int) ($medicament->getPrice() * 100),  // Convert to cents
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    
        return $this->redirect($session->url, 303);
    }
    
    

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
