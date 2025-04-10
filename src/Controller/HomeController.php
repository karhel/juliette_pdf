<?php

namespace App\Controller;

use App\Service\PdfGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    /**
     * ça c'est la route qui va correspondre à ta liste
     * elle va tester, si une variable avec l'ID de l'utilisateur créé est transmis elle va lancer le stream (download) du PDF
     */
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        if($request->get('user_created')) {
            return $this->redirectToRoute('app_pdf_stream', ['id' => $request->get('user_created')]);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Elle, elle sert à rien... comme d'autres (routes/personnes)
     */
    #[Route('/pdf-output', name: 'app_pdf_output')]
    public function outputPdf(PdfGenerator $pdfGenerator): Response
    {
        $content = $pdfGenerator->output("<h1>Hello World!</h1>");
        return new Response($content, 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * C'est la route qui va se charger de créer le PDF à partir de l'ID utilisateur
     */
    #[Route('/pdf-stream/{id}', name: 'app_pdf_stream')]
    public function streamPdf(PdfGenerator $pdfGenerator, int $id): Response
    {
        // Je renvois le fichier sous la forme d'un stream
        return $pdfGenerator->getStreamResponse("<h1>Hello World! $id</h1>", 'file.pdf');
    }

    /** 
     * La route va correspondre au traitement de ton formulaire de création
     * Une fois le formulaire validé, tu vas rediriger vers ta liste, en transmettant une variable avec l'ID du user créé
     */
    #[Route('/pdf-start-download', name: 'app_pdf_start')]
    public function downloadPdf(): Response
    {
        // Traitement du formulaire
        // ....


        // Si tout est ok je redirige vers la liste des users
        return $this->redirectToRoute('app_home', ['user_created' => 5], 307);
    }
}
