<?php

namespace App\Controller;

use App\Entity\Play;
use App\Entity\Tournament;
use App\Repository\PlayRepository;
use App\Repository\StudentRepository;
use App\Repository\TournamentRepository;
use App\Service\GetPointsForClassement;
use App\Service\UpdateClassement;
use DateTime;
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
        $this->denyAccessUnlessGranted('ROLE_USER');
        $groupe=$request->request->get('groupe');
        $title=$request->request->get('title');
        $points=$request->request->get('points');
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
            $tournament->setPoints($points);
            $tournament->setGoalaverage(0);
            $tournament->setUser($this->getUser());

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
        $this->denyAccessUnlessGranted('ROLE_USER');
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
    public function match(Request $request, EntityManagerInterface $em,TournamentRepository $tournamentRepository, UpdateClassement $updateClassement, GetPointsForClassement $getPointsForClassement):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //On récupère les éléments du formulaire et le titre du tournoi
        $gagnant=$request->request->get('gagnant');
        $perdant=$request->request->get('perdant');
        $arbitre=$request->request->get('arbitre');
        $scoreGagnant=$request->request->get('score_gagnant');
        $scorePerdant=$request->request->get('score_perdant');
        $title=$request->request->get('title');
        
        //On récupère l'entité gagnant et perdant dans la base
        $tournamentGagnant=$tournamentRepository->findOneBy(['title'=> $title,'Player'=>$gagnant]);
        $classGagnant=$tournamentGagnant->getClassement();
        
        $tournamentPerdant=$tournamentRepository->findOneBy(['title'=> $title,'Player'=>$perdant]);
        $classPerdant=$tournamentPerdant->getClassement();
        
        //On calcule la différence entre le classement du gagnant et celui du perdant
        $diffClassement=$classGagnant-$classPerdant;

        //On utilise le service GetPointsForClassement pour récupérer les points à attribuer au gagnant et au perdant
        $pointsGagnant=$getPointsForClassement->getPointsGagant($diffClassement,$this->getUser());
        dump($pointsGagnant);
        $pointsPerdant=$getPointsForClassement->getMatchLostPoints($this->getUser());
       
       
        //On met à jour les entités gagnant et perdant
        $tournamentGagnant->setPoints($tournamentGagnant->getPoints()+$pointsGagnant);
        $tournamentGagnant->setNbreMatch($tournamentGagnant->getNbreMatch()+1);
        $tournamentGagnant->setGoalaverage($tournamentGagnant->getGoalaverage()+$scoreGagnant-$scorePerdant);

        $tournamentPerdant->setPoints($tournamentPerdant->getPoints()+$pointsPerdant);
        $tournamentPerdant->setNbreMatch($tournamentPerdant->getNbreMatch()+1);
        $tournamentPerdant->setGoalaverage($tournamentPerdant->getGoalaverage()+$scorePerdant-$scoreGagnant);

        //On met à jour l'entité arbitre
        if(!str_contains($arbitre,'aucun')){
        $arbitreTournament=$tournamentRepository->findOneBy(['title'=> $title,'Player'=>$arbitre]);
        $arbitreTournament->setArbitre($arbitreTournament->getArbitre()+1);
        $em->persist($arbitreTournament);
        }

        $em->persist($tournamentGagnant);
        $em->persist($tournamentPerdant);
        
        //On crée une nouvelle entité Play pour enregistrer le match
        $play=new Play();
        $play->setGagnantName($gagnant);
        $play->setPerdantName($perdant);
        $play->setScoreGagnant($scoreGagnant);
        $play->setScorePerdant($scorePerdant);
        $play->setPointsGagnant($pointsGagnant);
        $play->setPointsPerdant($pointsPerdant);
        $play->setArbitre($arbitre);
        //On enregistre le classement avant le match pour pouvoir le récupérer en cas d'annulation du match
        $play->setClassementGagnant($classGagnant);
        $play->setClassementPerdant($classPerdant);

        $em->persist($play);
        $em->flush();

        $updateTournament=$tournamentRepository->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']);
        $updateClassement->update($title,$em,$tournamentRepository);

        $this->addFlash('success', 'Match enregistré avec succès, '.$gagnant.' a obtenu '.$pointsGagnant.' points et '.$perdant.' a obtenu '.$pointsPerdant.' points.');

        return $this->redirectToRoute('app_tournament_view', [
            //'tournament' => $updateTournament,
            'title' => $title,
           // 'date' => $date

        ]);
        
    }

    #[Route('/updateEleveTournament', name: 'app_tournament_update_eleve')]
    public function updateEleveTournament(Request $request, TournamentRepository $tournamentRepository):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $requ=json_decode($request->getContent(),true);
        $nomEleve=$requ['nomEleve'];
        $title=$requ['titre'];
        
        $tournament=$tournamentRepository->findBy(['Player'=> $nomEleve,'title'=>$title]);
        $point=$tournament[0]->getPoints();
        $nbreMatch=$tournament[0]->getNbreMatch();
        $arbitre=$tournament[0]->getArbitre();
        $goalaverage=$tournament[0]->getGoalaverage();
        $json = json_encode(['point'=>$point,'nbreMatch'=>$nbreMatch,'arbitre'=>$arbitre,'goalaverage'=>$goalaverage]);

        return new JsonResponse($json);
    }

    #[Route('/updateTournament', name: 'app_tournament_update')]
    public function updateTournament(Request $request, TournamentRepository $tournamentRepository, EntityManagerInterface $em, UpdateClassement $updateClassement):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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
        
        $updateClassement->update($title,$em,$tournamentRepository);

        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournamentRepository->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']),
            'title' => $title,
            'date' => $date

        ]);
    }    

    #[Route('/resetTournament/{title}', name: 'app_reset_delete')]
    public function deleteTournament(string $title, TournamentRepository $tournamentRepository, EntityManagerInterface $em):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $tournaments=$tournamentRepository->findBy(['title'=>$title]);
        foreach($tournaments as $tournament){
            $tournament->setClassement(1);
            $tournament->setPoints(0);
            $tournament->setNbreMatch(0);
            $tournament->setArbitre(0);
            $tournament->setGoalaverage(0);
            $em->persist($tournament);
        }
        $em->flush();
        
        return $this->render('tournament/index.html.twig', [
            'tournament' => $tournamentRepository->findBy(['title'=> $title], ['Points'=>'DESC','goalaverage'=>'DESC']),
            'title' => $title,
            'date' => $tournament->getCreatedAt()

        ]);
    }
    
    #[Route('/deleteMatch/{title}', name: 'app_tournament_delete_match')]
    public function deleteMatch(string $title, EntityManagerInterface $em,PlayRepository $playRepository):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $plays=$playRepository->findall();
        $play=end($plays);

        $gagnant=$play->getGagnantName();
        $perdant=$play->getPerdantName();
        $classementGagnant=$play->getClassementGagnant();
        $classementPerdant=$play->getClassementPerdant();
        $scoreGagnant=$play->getScoreGagnant();
        $scorePerdant=$play->getScorePerdant();
        $pointsGagnant=$play->getPointsGagnant();
        $pointsPerdant=$play->getPointsPerdant();
        $arbitre=$play->getArbitre();

        $tournamentGagnant=$em->getRepository(Tournament::class)->findOneBy(['title'=> $title,'Player'=>$gagnant]);
        $tournamentPerdant=$em->getRepository(Tournament::class)->findOneBy(['title'=> $title,'Player'=>$perdant]);
        $arbitreTournament=$em->getRepository(Tournament::class)->findOneBy(['title'=> $title,'Player'=>$arbitre]);

        $tournamentGagnant->setPoints($tournamentGagnant->getPoints()-$pointsGagnant);
        $tournamentGagnant->setNbreMatch($tournamentGagnant->getNbreMatch()-1);
        $tournamentGagnant->setGoalaverage($tournamentGagnant->getGoalaverage()-$scoreGagnant+$scorePerdant);
        $tournamentGagnant->setClassement($classementGagnant);

        $tournamentPerdant->setPoints($tournamentPerdant->getPoints()-$pointsPerdant);
        $tournamentPerdant->setNbreMatch($tournamentPerdant->getNbreMatch()-1);
        $tournamentPerdant->setGoalaverage($tournamentPerdant->getGoalaverage()-$scorePerdant+$scoreGagnant);
        $tournamentPerdant->setClassement($classementPerdant);

        if(!str_contains($arbitre,'aucun')){
        $arbitreTournament->setArbitre($arbitreTournament->getArbitre()-1);
        $em->persist($arbitreTournament);
        }
        $em->persist($tournamentGagnant);
        $em->persist($tournamentPerdant);

        $em->remove($play);
        $em->flush();
        
        return $this->redirectToRoute('app_tournament_view', [
            'title' => $title
        ]);
    }

    #[Route('/deleteTournament/{title}', name: 'app_tournament_delete')]
    public function delete(string $title, EntityManagerInterface $em, TournamentRepository $tournamentRepository):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $tournaments=$tournamentRepository->findBy(['title'=>$title]);
        foreach($tournaments as $tournament){
            $em->remove($tournament);
        }
        $em->flush();
        
        return $this->redirectToRoute('app_dashboard');
    }

}