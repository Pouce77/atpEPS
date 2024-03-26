<?php

namespace App\Controller;

use App\Repository\TournamentRepository;
use App\Service\PdfCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PdfController extends AbstractController
{
    #[Route('/pdf/{title}', name: 'app_pdf')]
    public function index(string $title,PdfCreator $pdfCreator,TournamentRepository $tournamentRepository): Response
    {
        $tournaments=$tournamentRepository->findby(['title'=>$title],['Points'=>'DESC','goalaverage'=>'DESC']);
        $date=$tournaments[0]->getCreatedAt();
        $html = $this->renderView('pdf/index.html.twig', [
            'tournaments' => $tournaments,
            'title' => $title,
            'date' => $date
        ]);
        $pdf = $pdfCreator->createPdf($html);
        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$title.'.pdf"',
        ]);
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }
}
