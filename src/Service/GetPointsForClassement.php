<?php

namespace App\Service;

use App\Entity\Points;
use App\Entity\Student;
use App\Repository\PointsRepository;
use Doctrine\ORM\EntityManagerInterface;

class GetPointsForClassement
{

    public function __construct(private PointsRepository $pointsRepository)
    {
        
    }

    public function getPointsGagant($diff,$user): int
    {
        global $pointGagnant;
        $points=$this->pointsRepository->findOneBy(['user'=>$user]);
        switch ($diff) {
        case $diff<=-5 :
                $pointGagnant=$points->getUnderPoints()['5'];
            break;
            case $diff===-4 :
                $pointGagnant=$points->getUnderPoints()['4'];
                break;
            case $diff===-3 :
                $pointGagnant=$points->getUnderPoints()['3'];
                break;
            case $diff===-2 :
                $pointGagnant=$points->getUnderPoints()['2'];
                break;
            case $diff===-1 :
                $pointGagnant=$points->getUnderPoints()['1'];
                break;
            case $diff===0 :
                $pointGagnant=$points->getUnderPoints()['1'];
                break;
            case $diff===1 :
                $pointGagnant=$points->getAbovePoints()['1'];
                break;
            case $diff===2 :
                $pointGagnant=$points->getAbovePoints()['2'];
                break;
            case $diff===3 :
                $pointGagnant=$points->getAbovePoints()['3'];
                break;
            case $diff===4 :
                $pointGagnant=$points->getAbovePoints()['4'];
                break;
            case $diff>5 :
                $pointGagnant=$points->getAbovePoints()['5'];
                break;
        default:
            # code...
            break;
       }
       if($pointGagnant==null)
         {
              $pointGagnant=$points->getAbovePoints()['1'];
         }
        return $pointGagnant;
    }

    public function getMatchLostPoints($user):int
    {

        $points=$this->pointsRepository->findOneBy(['user'=>$user]);
        $pointLost=$points->getMatchLostPoints();

        return $pointLost;
    }
}