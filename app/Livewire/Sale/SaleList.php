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
   
    //Propiedades clase
    public $search='';
    public $totalRegistros=0;
    public $cant=5;

    public $totalVentas=0;
    public $dateInicio;
    public $dateFin;

    public function render()
    {
        Cart::clear();
        
        if($this->search!=''){
            $this->resetPage();
        }

        $this->totalRegistros = Sale::count();
        
        $salesQuery = Sale::where('id','like','%'.$this->search.'%');

        if($this->dateInicio && $this->dateFin){
            $salesQuery = $salesQuery->whereBetween('fecha',[$this->dateInicio,$this->dateFin]);

            $this->totalVentas = $salesQuery->sum('total');
        }else{
            $this->totalVentas = Sale::sum('total');
        }

        $sales = $salesQuery
                ->orderBy('id','desc')
                ->paginate($this->cant);

        return view('livewire.sale.sale-list',[
            "sales" => $sales
        ]);
    }

    #[On('destroySale')]
    public function destroy($id){
        
        $sale = Sale::findOrFail($id);

        foreach($sale->items as $item){
            Product::find($item->id)->increment('stock',$item->qty);
            $item->delete();

        }

        $sale->delete();

        $this->dispatch('msg','Venta eliminada');
    }

    #[On('setDates')]
    public function setDates($fechaInicio,$fechaFinal){

        //dump($fechaInicio,$fechaFinal);

        $this->dateInicio = $fechaInicio;
        $this->dateFin = $fechaFinal;

    }



}
