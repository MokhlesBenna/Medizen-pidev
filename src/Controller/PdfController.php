<?php

// src/Controller/PdfController.php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Pdfcrowd\HtmlToPdfClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    private string $pdfCrowdUsername;  // Replace with your Pdfcrowd username
    private string $pdfCrowdApiKey;     // Replace with your actual API key

    public function __construct(string $pdfCrowdUsername, string $pdfCrowdApiKey)
    {
        // Trim any whitespaces from the API key
        $this->pdfCrowdUsername = trim($pdfCrowdUsername);
        $this->pdfCrowdApiKey = trim($pdfCrowdApiKey);
    }

    #[Route('/export/pdf', name: 'export_pdf')]
    public function exportPdf(CommandeRepository $commandeRepository): Response
    {
        try {
            $commandes = $commandeRepository->findAll();
            $html = $this->renderView('pdf/index.html.twig', ['commandes' => $commandes]);

            // create the API client instance
            $client = new HtmlToPdfClient($this->pdfCrowdUsername, $this->pdfCrowdApiKey);

            // run the conversion and get the PDF content
            $pdfContent = (string) $client->convertString($html);

            // Return the PDF as a downloadable response
            return new Response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="commands_export.pdf"',
            ]);
        } catch (\Pdfcrowd\Error $e) {
            // Log or handle the exception (e.g., return an error response)
            return new Response('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }
}
