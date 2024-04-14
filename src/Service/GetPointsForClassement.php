<?php

namespace App\Service;

use App\Entity\Points;
use App\Repository\PointsRepository;
use App\Service\OptionService;

class GetPointsForClassement
{

    public function __construct(private PointsRepository $pointsRepository, private OptionService $optionService)
    {
        
    }

    public function getPointsGagant($diff,$user)
    {
        global $pointGagnant;
        $points=$this->pointsRepository->findOneBy(['user'=>$user]);
        if($points==null){
            $points=new Points();
            $points=$this->optionService->getOptionPoints();
        }
        
        dump($points->getUnderPoints()['5']);
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
                dump($pointGagnant);
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

    public function getMatchLostPoints($user):float
    {

        $points=$this->pointsRepository->findOneBy(['user'=>$user]);
        if($points==null){
            $points=new Points();
            $points=$this->optionService->getOptionPoints();
        }
        $pointLost=$points->getMatchLostPoints();

        return $pointLost;
    }
}