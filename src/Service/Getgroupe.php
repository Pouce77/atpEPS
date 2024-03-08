<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class Getgroupe
{
  public function __construct(private EntityManagerInterface $entityManager)
  {
  
  } 

  public function getGroupes():array
  {
    $students = $this->entityManager->getRepository(Student::class)->findAll();
    $groupes = [];
    foreach ($students as $student) {
        if(!in_array($student->getGroupe(),$groupes)){
        array_push($groupes,$student->getGroupe());
        }
    }
    return $groupes;
  }
}