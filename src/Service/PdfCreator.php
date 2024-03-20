<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfCreator
{
    public function createPdf($html)
    {
        // Configure Dompdf according to your needs
       $pdfOptions = new Options();
       $pdfOptions->set('isRemoteEnabled', TRUE);
       
       // Instantiate Dompdf with our options
       $dompdf = new Dompdf($pdfOptions);

       // Load HTML to Dompdf
       $dompdf->loadHtml($html);
       
       // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
       $dompdf->setPaper('A4', 'portrait');

       // Render the HTML as PDF
       $dompdf->render();

       return $dompdf;
        
    }

}