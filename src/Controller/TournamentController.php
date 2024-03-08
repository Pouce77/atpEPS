<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Tournament;
use App\Repository\StudentRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TournamentController extends AbstractController
{
    #[Route('/tournament', name: 'app_tournament')]
    public function index(Request $request, StudentRepository $studentRepository, EntityManagerInterface $em): Response
    {
        $groupe=$request->request->get('groupe');
        $tournament = new Tournament();
        $students=$studentRepository->findBy(['groupe'=>$groupe]);

        foreach ($students as $student) {
            $tournament = new Tournament();
            $tournament->setCreatedAt(DateTime::createFromFormat('d/m/Y', date('d/m/Y')));
            $tournament->setTitle('Tournoi '.$groupe);
            $tournament->setNbreMatch(0);
            $tournament->setClassement(1);
            $tournament->setPlayer($student->getNom().' '.$student->getPrenom());

            $em->persist($tournament);
            $em->flush();        
        }

        $tournaments=$em->getRepository(Tournament::class)->findBy(['title'=>'Tournoi '.$groupe]);

        return $this->render('tournament/index.html.twig', [
            'groupe' => $groupe,
            'tournament' => $tournaments,
        ]);
    }
}
