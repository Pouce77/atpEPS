<?php

namespace App\Service;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class ImportCSV
{
  public function __construct(private EntityManagerInterface $entityManager)
  {
  
  } 

  public function import($csv, $group,$user):bool
  {
    $lineFromCSV=read($csv); 
    $isVerified=isVerified($lineFromCSV);

    if ($isVerified)
    {
      foreach ($lineFromCSV as $stud)
      {
        if ($stud==false){
          //ignore the empty line
        }else{
        $student=new Student();
        $student->setNom($stud['0']);
        $student->setPrenom($stud['1']);
        $student->setGroupe($group);
        $student->setUser($user);

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
function isVerified($line):bool
{
  foreach ($line as $stud){
  if($stud==false)
  {
    //ignore the empty line
  }else{
      if (count($stud)!=2)
      {
        return false;
      }      
  }
}
return true;
}
