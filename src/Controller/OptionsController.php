<?php

namespace App\Controller;

use App\Entity\Points;
use App\Form\PointsType;
use App\Repository\PointsRepository;
use App\Service\OptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OptionsController extends AbstractController
{
    #[Route('/options', name: 'app_options')]
    public function index(OptionService $optionService,Request $request, EntityManagerInterface $em,PointsRepository $pointsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $points=$pointsRepository->findOneBy(['user'=>$this->getUser()]);
        if($points==null){
            $points=new Points();
            $points=$optionService->getOptionPoints();
        }
        $form = $this->createForm(PointsType::class, $points);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $points->setUser($this->getUser());
            $em->persist($points);
            $em->flush();

            $matchLostPoints=$points->getMatchLostPoints();
            $abovePoints=$points->getAbovePoints();
            $underPoints=$points->getUnderPoints();

            return $this->render('options/index.html.twig', [
                'matchLostPoints' => $matchLostPoints,
                'abovePoints' => $abovePoints,
                'underPoints' => $underPoints,
                'form' => $form
            ]);
        }

        $matchLostPoints = $points->getMatchLostPoints();
        $abovePoints = $points->getAbovePoints();
        $underPoints = $points->getUnderPoints();

        return $this->render('options/index.html.twig', [
            'matchLostPoints' => $matchLostPoints,
            'abovePoints' => $abovePoints,
            'underPoints' => $underPoints,
            'form' => $form
        ]);
    }
}
