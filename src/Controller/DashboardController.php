<?php

namespace App\Controller;

use App\Repository\StudentRepository;
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

    #[Route('/groupe', name: 'app_groupe')]
    public function groupe(): Response
    {
        $groupes = ['Groupe1', 'Groupe2', 'Groupe3'];
        return $this->render('dashboard/groupe.html.twig', [
            'groupes' => $groupes,
        ]);
    }

    #[Route('/groupe/{id}', name: 'app_groupe_view')]
    public function view(string $id, StudentRepository $student): Response
    {
        $groupe = $student->findBy(['groupe' => $id]);
        return $this->render('dashboard/groupe_view.html.twig', [
            'groupe' => $groupe,
            'id' => $id,
        ]);
    }
}