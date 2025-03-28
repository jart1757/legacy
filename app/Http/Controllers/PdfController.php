<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shop;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PdfController extends Controller
{
    public function invoice(Sale $sale){

        $shop = Shop::first();
        
        $pdf = Pdf::loadView('sales.invoice', compact('sale','shop'));
        return $pdf->stream('invoice.pdf');
        
    }
    public function report(Sale $sale)
    {
        // Convertir la venta en una colección
        $sales = collect([$sale]);
        
        // Definir la fecha final (por ejemplo, la fecha actual)
        $fechaFinal = now();
        $fechaInicio = now();
        
        $pdf = Pdf::loadView('sales.report', compact('sales', 'fechaInicio','fechaFinal'));
        return $pdf->stream('invoice.pdf');
        
    }
    

}
