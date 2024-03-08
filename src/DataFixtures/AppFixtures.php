<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 20; $i++){
            $student = new Student();
            $student->setNom($faker->firstName);
            $student->setPrenom($faker->lastName);
            $student->setGroupe($faker->randomElement(['Groupe1', 'Groupe2', 'Groupe3']));
            $manager->persist($student);
            $manager->flush();
        }
    }
}
