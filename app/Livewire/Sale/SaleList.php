<?php

namespace App\Livewire\Sale;

use App\Models\Cart;
use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Ventas')]
class SaleList extends Component
{
    use WithPagination;

    public $search = '';
    public $dateInicio, $dateFin;
    public $totalRegistros, $totalVentas;
    public $cant = 10; // Número de registros por página

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

        // Aplicar filtro por fechas si están definidas
        if ($this->dateInicio && $this->dateFin) {
            $salesQuery->whereBetween('fechaing', [$this->dateInicio, $this->dateFin]);
            $this->totalVentas = $salesQuery->sum('total');
        } else {
            $this->totalVentas = Sale::sum('total');
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
        $this->dateInicio = $fechaInicio;
        $this->dateFin = $fechaFinal;
    }
}