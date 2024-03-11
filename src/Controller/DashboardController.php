<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use App\Repository\StudentRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $tournaments=$tournamentRepository->findAll();
        $titles=[];
        foreach ($tournaments as $tournament) {
            if(!in_array($tournament->getTitle(),$titles)){
                array_push($titles,$tournament->getTitle());
            }
        }
        
        return $this->render('dashboard/index.html.twig', [
            'titles' => $titles
        ]);
    }

    #[Route('/groupe', name: 'app_groupe')]
    public function groupe(EntityManagerInterface $em, GroupeRepository $groupeRepository,Request $request,ValidatorInterface $validatorInterface): Response
    {
        $groupe = new Groupe();
        
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            
            $em->persist($groupe);
            $em->flush();
        }

        $errors=$validatorInterface->validate($groupe);
        // Vérifiez s'il y a des erreurs de validation
        if (count($errors) > 0) {
            // Construisez un tableau pour stocker les messages d'erreur
            $errorMessages = [];
            
            // Parcourez les erreurs et stockez-les dans le tableau
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
                dump($error->getMessage());
            }

            $this->addFlash('danger', implode("\n", $errorMessages));
        }

        $groupes=$groupeRepository->findAll();

        return $this->render('dashboard/groupe.html.twig', [
            'form' => $form->createView(),
            'groups' => $groupes
        ]);
    }

    #[Route('/groupe/{id}', name: 'app_groupe_view')]
    public function view(string $id, StudentRepository $student): Response
    {
        $groupe = $student->findBy(['groupe' => $id]);
        return $this->render('dashboard/groupe_view.html.twig', [
            'groupe' => $groupe,
            'id' => $id,
        ]);
    }

    #[Route('/groupe/delete/{id<\d+>}', name: 'app_groupe_delete')]
    public function delete(Groupe $groupe, EntityManagerInterface $em, GroupeRepository $groupeRepository): Response
    {
        
        $em->remove($groupe);
        $em->flush();
        
        return $this->redirectToRoute('app_groupe');
    }
}
