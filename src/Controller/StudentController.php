<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentController extends AbstractController
{
    #[Route('/student/add/{groupe}', name: 'app_student_add')]
    public function add(string $groupe, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $student=new Student();
        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $student->setGroupe($groupe);
            $student->setUser($this->getUser());
            $em=$doctrine->getManager();
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('app_groupe_view',['id'=>$groupe]);
        }
        return $this->render('student/index.html.twig', [
            'form' => $form,
            'id' => $groupe,
        ]);
    }

    #[Route('/student/delete/{id<\d+>}', name: 'app_student_delete')]
    public function delete(Student $student, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $groupe=$student->getGroupe();
        $em=$doctrine->getManager();
        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('app_groupe_view',['id'=>$groupe]);
    }

    #[Route('/student/update/{id<\d+>}', name: 'app_student_update')]
    public function update(Request $request,Student $student, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $id=$student->getGroupe();
        $form=$this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           $entityManager=$doctrine->getManager();
           $entityManager->flush();
           
           return $this->redirectToRoute('app_groupe_view',['id'=>$id]);
        }

        return $this->render("student/update.html.twig", [
            "form" => $form,
            "studentid" => $student->getId()
        ]);
    }
}
