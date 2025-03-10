<?php

namespace App\Livewire\Sale;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductRow extends Component
{
    public Product $product;
    public $stock;
    public $stockLabel;
    public $category_id = null;

    protected function getListeners(){

        return [
            "decrementStock.{$this->product->id}" => "decrementStock",
            "incrementStock.{$this->product->id}" => "incrementStock",
            "refreshProducts" => "mount",
            "devolverStock.{$this->product->id}" => "devolverStock",
            "updateCategory" => "updateCategory",  // Escuchar evento para actualizar categoría
            
        ];
    }
    public function render()
{
    $productos = Product::when($this->category_id, function ($query) {
        $query->where('category_id', $this->category_id);
    })->get();

    $this->stockLabel = $this->stockLabel();

    return view('livewire.sale.product-row', [
        'productos' => $productos
    ]);
}

    

    public function mount(){
        $this->stock = $this->product->stock;
    }

    public function addProduct(Product $product){

        if($this->stock==0){
            return;
        }

        $this->dispatch('add-product',$product);
        $this->stock--;
    }

    public function decrementStock(){
        $this->stock--;
    }

    public function incrementStock(){

        if($this->stock==$this->product->stock-1){
            return;
        }

        $this->stock++;
    }

    public function devolverStock($qty){
        $this->stock = $this->stock+$qty;
    }

    public function stockLabel(){

        if($this->stock<=$this->product->stock_minimo){
            return '<span class="badge badge-pill badge-danger">'.$this->stock.'</span>';
        }else{
            return '<span class="badge badge-pill badge-success">'.$this->stock.'</span>';
        }
    }
    // Método para actualizar la categoría
    public function updateCategory($data)
    {
        $this->category_id = $data['category_id'];
        $this->dispatch('refreshProducts'); // Refrescar productos
    }
    
}