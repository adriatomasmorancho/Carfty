<?php


namespace App\Http\Controllers;

// use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PdfController extends Controller
{
    public function generatePDF(Request $request)
    {
        try {

            $data = $request->all();


            $view = view('pdf.historial_pedidos', compact('data'));


            $dompdf = new Dompdf();


            $dompdf->loadHtml($view->render());


            $dompdf->render();

            Log::channel('desarrollo')->info('La funció generatePDF de PdfController funciona correctament');
            return $dompdf->stream('archivo.pdf');
        } catch (Exception $e) {
            Log::error("Error en la función generatePDF: {$e->getMessage()}");

            return redirect()->route('error.generic');
        }
    }
}