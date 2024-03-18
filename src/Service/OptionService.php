<?php

namespace App\Service;

use App\Entity\Points;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OptionService extends AbstractController
{
    public function getOptionPoints(): Points
    {
        $points= new Points();
        $json = file_get_contents($this->getParameter('options'));
        $data = json_decode($json, true);
        $points->setAbovePoints($data['abovePoints']);
        $points->setUnderPoints($data['underPoints']);
        $points->setMatchLostPoints($data['lostPoints']);

        return $points;
    }
}