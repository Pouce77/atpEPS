<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/dashboard/groupe', name: 'app_groupe')]
    public function groupe(): Response
    {
        $groupes = ['Groupe 1', 'Groupe 2', 'Groupe 3'];
        return $this->render('dashboard/groupe.html.twig', [
            'groupes' => $groupes,
        ]);
    }
}
