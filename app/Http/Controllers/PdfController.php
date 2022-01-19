<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;

class PdfController extends Controller
{
    public function index()
    {
        $view = view('pdf');

        $dompdf = new Dompdf();
        $dompdf->loadHtml($view);

        $dompdf->setPaper('A4', 'landscape');

    // Render the HTML as PDF
        $dompdf->render();

    // Output the generated PDF to Browser
            $dompdf->stream("test.pdf",array("Attachment" => false));
    }
}
