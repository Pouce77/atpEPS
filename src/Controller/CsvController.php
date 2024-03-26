<?php

namespace App\Controller;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CsvController extends AbstractController
{
    #[Route('/csv/{title}', name: 'app_create_csv')]
    public function index(string $title,TournamentRepository $tournamentRepository): Response
    {
        $tournaments=$tournamentRepository->findby(['title'=>$title],['Points'=>'DESC','goalaverage'=>'DESC']);
        $myVariableCSV = "Classement;Nom;Points de classement;Nombre de matchs;arbitrage;Goalaverage\n";
        foreach ($tournaments as $tournament) {
            $myVariableCSV .= $tournament->getClassement().";".$tournament->getPlayer().";".$tournament->getPoints().";".$tournament->getNbreMatch().";".$tournament->getArbitre().";".$tournament->getGoalaverage()."\n";
        }
        // Conversion de la chaîne en UTF-8 si elle n'est pas déjà encodée
        if (!mb_check_encoding($myVariableCSV, 'UTF-8')) {
            $myVariableCSV = mb_convert_encoding($myVariableCSV, 'UTF-8');
        }
        return new Response(
            $myVariableCSV,
            200,
            [
          //Définit le contenu de la requête en tant que fichier Excel
              'Content-Type' => 'text/csv',
          //On indique que le fichier sera en attachment donc ouverture de boite de téléchargement ainsi que le nom du fichier
              "Content-disposition" => "inline; filename=".$title.".csv"
           ]
     );
    }
}