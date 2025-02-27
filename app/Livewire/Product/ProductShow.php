<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Ver producto')]
class ProductShow extends Component
{
   public Product $product;
    
    public function render()
    {
        return view('livewire.product.product-show');
    }
}
