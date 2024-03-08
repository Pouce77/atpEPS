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
        return $this->render('dashboard/import.html.twig',[
            'group' => $id
        ]);
    }

    #[Route('/import/csv/{id}', name:"app_csv")]
    public function csv(string $id, ImportCSV $import, HttpFoundationRequest $request):Response
    {
        $csv=$request->files->get('myfile');
        
       $isImport = $import->import($csv,$id);

         if($isImport){
              $this->addFlash('success','Importation réussie');
         }

        return $this->redirectToRoute('app_groupe_view',['id'=> $id]);
        
    }
}