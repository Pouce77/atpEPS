<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class ImportCSV
{
  public function __construct(private EntityManagerInterface $entityManager)
  {
  
  } 

  public function import($csv, $group):bool
  {
    $fileFromCSV=read($csv); 
    $isVerified=isVerified($fileFromCSV);

    if ($isVerified)
    {
      foreach ($fileFromCSV as $stud)
      {
        if ($stud==false){

        }else{
        $student=new Student();
    
        $student->setNom($stud['0']);
        $student->setPrenom($stud['1']);
        $student->setGroupe($group);

        $this->entityManager->persist($student);
        $this->entityManager->flush();
        }
      } 
      return true;
    }else{
      return false;
    }
  }
}

//read the CSV file and return an array with the line 
function read($file)
{
  $fileCSV = fopen($file, 'r');
  while (!feof($fileCSV) ) 
  {
    $line[] = fgetcsv($fileCSV, 1024);
  }
  fclose($fileCSV);
  return $line;
}

//Check if the file is well formatted
function isVerified($file):bool
{
  foreach ($file as $stud){
  if($stud==false)
  {
    //ignore the empty line
  }else{
    
  }
}
return true;
}
