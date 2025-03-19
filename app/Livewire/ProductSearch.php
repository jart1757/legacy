<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class ProductSearch extends Component
{
    public $category_id = ''; // Variable para la categoría seleccionada

    public function render()
    {
        // Obtener todas las categorías
        $categories = Category::all();

        // Filtrar productos según la categoría seleccionada
        $products = Product::when($this->category_id, function ($query) {
            $query->where('category_id', $this->category_id);
        })->get();

        return view('livewire.product-search', compact('categories', 'products'));
    }
}
