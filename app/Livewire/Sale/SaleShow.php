<?php

namespace App\Livewire\Sale;

use App\Models\Sale;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Ventas')]
class SaleShow extends Component
{
    public Sale $sale;
    
    public function render()
    {
        return view('livewire.sale.sale-show');
    }
}
