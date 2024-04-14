<?php

namespace App\Service;

use App\Entity\Groupe;
use App\Entity\Student;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;

class Getgroupe
{
  public function __construct(private EntityManagerInterface $entityManager,private GroupeRepository $repository)
  {
  
  } 

  public function getGroupes($user):array
  {
    $groups = $this->repository->findByUser($user);
    $groupes=[];
    foreach($groups as $group){
      array_push($groupes,$group->getName());
    }
    
    return $groupes;
  }
}