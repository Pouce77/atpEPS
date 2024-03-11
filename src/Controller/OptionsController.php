<?php

namespace App\Controller;

use App\Service\OptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OptionsController extends AbstractController
{
    #[Route('/options', name: 'app_options')]
    public function index(OptionService $optionService): Response
    {
        $points=$optionService->getOptionPoints();
        dump($points);
        return $this->render('options/index.html.twig', [
            'points' => $points
        ]);
    }
}
