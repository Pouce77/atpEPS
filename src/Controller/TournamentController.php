<?php

namespace App\Controller;

use App\Entity\Play;
use App\Entity\Student;
use App\Entity\Tournament;
use App\Repository\StudentRepository;
use App\Repository\TournamentRepository;
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
        $title=$request->request->get('title');
        $tournament = new Tournament();
        $students=$studentRepository->findBy(['groupe'=>$groupe]);

        foreach ($students as $student) {
            $tournament = new Tournament();
            $tournament->setCreatedAt(DateTime::createFromFormat('d/m/Y', date('d/m/Y')));
            $tournament->setTitle($title);
            $tournament->setNbreMatch(0);
            $tournament->setClassement(1);
            $tournament->setPlayer($student->getNom().' '.$student->getPrenom());
            $tournament->setArbitre(0);

            $em->persist($tournament);
            $em->flush();        
        }

        $tournament=$em->getRepository(Tournament::class)->findBy(['title'=> $title]);
        $date=$tournament[0]->getCreatedAt();

        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournament,
            'title' => $title,
            'date' => $date
        ]);
    }

    #[Route('/tournament/{title}', name: 'app_tournament_view')]
    public function view(string $title, EntityManagerInterface $em): Response
    {
        str_replace('%20',' ',$title);
        $tournament=$em->getRepository(Tournament::class)->findBy(['title'=> $title]);
        $date=$tournament[0]->getCreatedAt();
      
        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournament,
            'title' => $title,
            'date' => $date

        ]);
    }

    #[Route('/match', name: 'app_tournament_match')]
    public function match(Request $request, EntityManagerInterface $em,TournamentRepository $tournamentRepository):Response
    {
        $gagnant=$request->request->get('gagnant');
        $perdant=$request->request->get('perdant');
        $arbitre=$request->request->get('arbitre');
        $scoreGagnant=$request->request->get('score_gagnant');
        $scorePerdant=$request->request->get('score_perdant');
        $title=$request->request->get('title');
        $date=$request->request->get('date');
        $tournament=$tournamentRepository->findBy(['title'=> $title]);

        $play=new Play();

        $play->setGagnantName($gagnant);
        $play->setPerdantName($perdant);
        $play->setScoreGagnant($scoreGagnant);
        $play->setScorePerdant($scorePerdant);
        $play->setArbitre($arbitre);

        $em->persist($play);
        $em->flush();

        $this->addFlash('success', 'Match enregistré avec succès');

        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournament,
            'title' => $title,
            'date' => $date

        ]);
    }
}    

