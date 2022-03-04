<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;


class PdfService
{

    private $dompdf;
    public function __construct()
    {
        $this->dompdf = new Dompdf();
        $options = new Options();
        $options->setDefaultFont('Courier');
        $this->dompdf->setOptions($options);
    }

    public function showPdfFile($html)
    {
        $this->dompdf->loadHtml($html);
        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF to Browser
        $this->dompdf->stream('details.pdf', ['attachement' => false]);
    }
}
