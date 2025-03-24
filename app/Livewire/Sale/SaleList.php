<?php

namespace App\Livewire\Sale;

use App\Models\Cart;
use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Title('Ventas')]
class SaleList extends Component
{
    use WithPagination;

    public $search = '';
    public $dateInicio, $dateFin;
    public $totalRegistros, $totalVentas;
    public $cant = 10; // Número de registros por página
    public $fechaInicio;
    public $fechaFinal;

    public function render()
    {
        $this->totalRegistros = Sale::count();
    
        $salesQuery = Sale::query();
    
        // Aplicar búsqueda por ID o por nombre de delivery
        if ($this->search) {
            $salesQuery->where(function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('delivery', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
    
        if (!empty($this->fechaInicio) && !empty($this->fechaFinal)) {
            $salesQuery->whereBetween('created_at', [
                \Carbon\Carbon::parse($this->fechaInicio)->startOfDay(),
                \Carbon\Carbon::parse($this->fechaFinal)->endOfDay()
            ]);
            $this->totalVentas = $salesQuery->sum('total');
        }
        
    
        $sales = $salesQuery->orderBy('id', 'desc')->paginate($this->cant);
    
        return view('livewire.sale.sale-list', compact('sales'));
    }
    
    #[On('destroySale')]
public function destroy($id)
{
    $sale = Sale::findOrFail($id);

    // Agrupar cantidades a revertir por nombre de producto
    $revertGroups = [];
    foreach ($sale->items as $item) {
        if (isset($revertGroups[$item->name])) {
            $revertGroups[$item->name] += $item->qty;
        } else {
            $revertGroups[$item->name] = $item->qty;
        }
        $item->delete();
    }

    // Revertir (sumar) el stock de todos los productos agrupados por nombre
    foreach ($revertGroups as $name => $totalQty) {
        Product::where('name', $name)->increment('stock', $totalQty);
    }

    $sale->delete();

    $this->dispatch('msg', 'Venta eliminada con éxito.');
}

    

#[On('setDates')]
public function setDates($fechaInicio, $fechaFinal)
{
    $this->fechaInicio = $fechaInicio;
    $this->fechaFinal = $fechaFinal;
}


    public function mount()
    {
        $this->fechaInicio = now()->startOfYear()->format('Y-m-d');
        $this->fechaFinal = now()->format('Y-m-d');
    }

public function exportPDF()
{
    $salesQuery = Sale::query();

    if ($this->search) {
        $salesQuery->where(function ($query) {
            $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhereHas('delivery', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        });
    }

    if (!empty($this->fechaInicio) && !empty($this->fechaFinal)) {
        $salesQuery->whereBetween('created_at', [
            \Carbon\Carbon::parse($this->fechaInicio)->startOfDay(),
            \Carbon\Carbon::parse($this->fechaFinal)->endOfDay()
        ]);
    }

    $sales = $salesQuery->get();

    if ($sales->isEmpty()) {
        session()->flash('msg', 'No hay ventas en el rango de fechas seleccionado.');
        return;
    }

    $pdf = Pdf::loadView('sales.report', [
        'sales' => $sales,
        'fechaInicio' => $this->fechaInicio,
        'fechaFinal' => $this->fechaFinal
    ]);

    return response()->streamDownload(
        fn () => print($pdf->output()), 
        "Reporte_Ventas_{$this->fechaInicio}_{$this->fechaFinal}.pdf"
    );
}


}