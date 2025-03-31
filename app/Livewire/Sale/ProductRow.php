<?php

namespace App\Livewire\Sale;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;



class ProductRow extends Component
{
    use WithPagination; // Habilita la paginación en Livewire
    public Product $product;
    public $stock;
    public $stockLabel;
    public $category_id = null;
    public $search = ''; // Nueva variable para la búsqueda
    public $cant = 10; // Cantidad de registros a mostrar

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
       
        $productos = Product::where('name','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate(10);

        $this->stockLabel = $this->stockLabel();
    
        return view('livewire.sale.product-row', [
            'productos' => $productos,
            'categories' => \App\Models\Category::all() // Pasar las categorías a la vista
        ]);
    }
    

    public function mount(){
        $this->stock = $this->product->stock;
        $this->cant = 10; // Forzar que sean 10 registros por página
    }

    public function addProduct(Product $product)
    {
        if ($product->stock == 0) {
            return;
        }
    
        // Calcular nuevo stock
        $newStock = $product->stock - 1;
    
        // Verificar si hay stock suficiente
        if ($newStock < 0) {
            return;
        }
    
        // Actualizar stock de todos los productos con el mismo nombre
        Product::where('name', $product->name)->update(['stock' => $newStock]);
    
        // Refrescar el stock en la interfaz
        $this->stock = Product::find($product->id)->stock;
    
        // Emitir evento para actualizar la vista
        $this->dispatch('add-product', $product);
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