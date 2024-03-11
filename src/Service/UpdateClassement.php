<?php

namespace App\Service;

use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateClassement
{
    public function update(string $title, EntityManagerInterface $em, TournamentRepository $tournamentRepository)
    {
        $updateTournaments=$tournamentRepository->findBy(['title'=> $title],['Points'=>'DESC','goalaverage'=>'DESC']);
        $points=$updateTournaments[0]->getPoints();

        for ($i=0;$i<count($updateTournaments);$i++) {
            if($i==0){
                $updateTournaments[$i]->setClassement($i+1);
                $points=$updateTournaments[$i]->getPoints();
            }else{
                if($updateTournaments[$i]->getPoints()==$points){
                    $updateTournaments[$i]->setClassement($updateTournaments[$i-1]->getClassement());
                    $points=$updateTournaments[$i]->getPoints();  
                }else{
                    $updateTournaments[$i]->setClassement($i+1);
                    $points=$updateTournaments[$i]->getPoints();
                }
            }
            $em->persist($updateTournaments[$i]);
        }
        $em->flush();
    }

}