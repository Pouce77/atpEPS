<?php

namespace App\Controller;

use App\Service\ImportCSV;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ImportController extends AbstractController
{
    #[Route('/import/{id}', name:"app_import")]
    public function import(string $id):Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/import.html.twig',[
            'group' => $id
        ]);
    }

    #[Route('/import/csv/{id}', name:"app_csv")]
    public function csv(string $id, ImportCSV $import, HttpFoundationRequest $request):Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $csv=$request->files->get('myfile');
        $type=pathinfo($csv->getClientOriginalName(), PATHINFO_EXTENSION);
        
        if (!str_contains('csv' , $type)) {
            $this->addFlash('danger','Le fichier doit être de type .csv');
            return $this->redirectToRoute('app_import',['id'=> $id]);
        }
        
       $isImport = $import->import($csv,$id,$this->getUser());

         if($isImport){
              $this->addFlash('success','Importation réussie');
         }else{
                $this->addFlash('danger','Erreur lors de l\'importation. Le fichier ne semble pas être formater correctement. Le séparateur csv doit être une virgule.');
         }

        return $this->redirectToRoute('app_groupe_view',['id'=> $id]);
        
    }
}