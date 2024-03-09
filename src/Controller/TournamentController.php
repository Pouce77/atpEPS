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
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $tournament->setPoints(0);
            $tournament->setGoalaverage(0);

            $em->persist($tournament);
            $em->flush();        
        }

        $tournament=$em->getRepository(Tournament::class)->findBy(['title'=> $title],['Points'=>'DESC','goalaverage'=>'DESC']);
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
        $tournament=$em->getRepository(Tournament::class)->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']);
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
        
        foreach($tournament as $tourna){
           if($tourna->getPlayer()==$gagnant){
               $tourna->setNbreMatch($tourna->getNbreMatch()+1);
               $tourna->setPoints($tourna->getPoints()+3);
               $tourna->setGoalaverage($tourna->getGoalaverage()+$scoreGagnant-$scorePerdant);
            }
            if($tourna->getPlayer()==$perdant){
                $tourna->setNbreMatch($tourna->getNbreMatch()+1);
                $tourna->setPoints($tourna->getPoints()+1);
                $tourna->setGoalaverage($tourna->getGoalaverage()+$scorePerdant-$scoreGagnant);
            }
            if($tourna->getPlayer()==$arbitre){
                $tourna->setArbitre($tourna->getArbitre()+1);
            }
            $em->persist($tourna);
        }

        $play=new Play();
        $play->setGagnantName($gagnant);
        $play->setPerdantName($perdant);
        $play->setScoreGagnant($scoreGagnant);
        $play->setScorePerdant($scorePerdant);
        $play->setArbitre($arbitre);

        $em->persist($play);
        $em->flush();
        

        $this->addFlash('success', 'Match enregistré avec succès');

        $updateTournament=$em->getRepository(Tournament::class)->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']);

        return $this->render('tournament/index.html.twig', [
            'tournament' => $updateTournament,
            'title' => $title,
            'date' => $date

        ]);
    }

    #[Route('/updateEleveTournament', name: 'app_tournament_update_eleve')]
    public function updateEleveTournament(Request $request, TournamentRepository $tournamentRepository):Response
    {
        $requ=json_decode($request->getContent(),true);
        $nomEleve=$requ['nomEleve'];
        $title=$requ['titre'];
        
        $tournament=$tournamentRepository->findBy(['Player'=> $nomEleve,'title'=>$title]);
        dump($tournament[0]);
        $point=$tournament[0]->getPoints();
        $nbreMatch=$tournament[0]->getNbreMatch();
        $arbitre=$tournament[0]->getArbitre();
        $goalaverage=$tournament[0]->getGoalaverage();
        $json = json_encode(['point'=>$point,'nbreMatch'=>$nbreMatch,'arbitre'=>$arbitre,'goalaverage'=>$goalaverage]);
        dump($json);
        return new JsonResponse($json);
    }

    #[Route('/updateTournament', name: 'app_tournament_update')]
    public function updateTournament(Request $request, TournamentRepository $tournamentRepository, EntityManagerInterface $em):Response
    {
        $requ=$request->request->all();
        $title=$requ['titre'];
        $player=$requ['nom'];
        $tournament=$tournamentRepository->findOneBy(['Player'=> $player,'title'=>$title]);

        $tournament->setPoints($requ['point']);
        $tournament->setNbreMatch($requ['nbreMatch']);
        $tournament->setArbitre($requ['arbitrage']);
        $tournament->setGoalaverage($requ['goalaverage']);
        $tournament->setPlayer($requ['nom']);

        $date=$tournament->getCreatedAt();
        
        $em->persist($tournament);
        $em->flush();
        
        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournamentRepository->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']),
            'title' => $title,
            'date' => $date

        ]);
    }    
}    

