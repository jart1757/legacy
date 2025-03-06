<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

#[Title('Productos')]
class ProductComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    //Propiedades clase
    public $search='';
    public $totalRegistros=0;
    public $cant=5;

    //Propiedades modelo
    public $Id=0;
    public $name;
    public $category_id;
    public $descripcion;
    public $precio_compra;
    public $precio_venta;
    public $codigo_barras;
    public $stock=0;
    public $stock_minimo=10;
    public $fecha_vencimiento;
    public $active=1;
    public $image;
    public $imageModel;
    

    public function render()
    {
        
        $this->totalRegistros = Product::count();

        $products = Product::where('name','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate($this->cant);

        return view('livewire.product.product-component',[
            'products' => $products
        ]);
    }

    #[Computed()]
    public function categories(){
        return Category::all();
    }

    public function create(){

        $this->Id=0;

        $this->clean();

        $this->dispatch('open-modal','modalProduct');
    }

    // Crear producto
    public function store(){
        //  dump('Crear producto');

        $rules = [
            'name' => 'required|min:5|max:255|unique:products',
            'descripcion' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ];


        $this->validate($rules);

        $product = new Product();
     
         $product->name = $this->name; 
         $product->descripcion = $this->descripcion;
         $product->precio_compra = $this->precio_compra;
         $product->precio_venta = $this->precio_venta;
         $product->stock = $this->stock;
         $product->stock_minimo = $this->stock_minimo;
         $product->codigo_barras = $this->codigo_barras;
         $product->fecha_vencimiento = $this->fecha_vencimiento;
         $product->category_id = $this->category_id;
         $product->active = $this->active;
         $product->save();

        if($this->image){
            $customName = 'products/'.uniqid().'.'.$this->image->extension();
            $this->image->storeAs('public',$customName);
            $product->image()->create(['url'=>$customName]);
        }

        $this->dispatch('close-modal','modalProduct');
        $this->dispatch('msg','Producto creado correctamente.');
        $this->clean();
        
    }

    public function edit(Product $product){
      
        $this->clean();

        $this->Id = $product->id;
        $this->name = $product->name;
        $this->descripcion = $product->descripcion;
        $this->precio_compra = $product->precio_compra;
        $this->precio_venta = $product->precio_venta;        
        $this->stock = $product->stock;
        $this->stock_minimo = $product->stock_minimo;
        $this->imageModel = $product->imagen;
        $this->codigo_barras = $product->codigo_barras;
        $this->fecha_vencimiento = $product->fecha_vencimiento;
        $this->active = $product->active;
        $this->category_id = $product->category_id;

        $this->dispatch('open-modal','modalProduct');


        // dump($category);
    }

    public function update(Product $product){
        // dump($category);
        $rules = [
            'name' => 'required|min:5|max:255|unique:products,id,'.$this->Id,
            'descripcion' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ];


        $this->validate($rules);

        $product->name = $this->name;
        $product->descripcion = $this->descripcion;
        $product->precio_compra = $this->precio_compra;
        $product->precio_venta = $this->precio_venta;
        $product->stock = $this->stock;
        $product->stock_minimo = $this->stock_minimo;
        //$product->image = $this->imageModel;
        $product->codigo_barras = $this->codigo_barras;
        $product->fecha_vencimiento = $this->fecha_vencimiento;
        $product->active = $this->active;
        $product->category_id = $this->category_id;

        $product->update();

        if($this->image){

            if($product->image!=null){
                Storage::delete('public/'.$product->image->url);
                $product->image()->delete();
            }

            $customName = 'products/'.uniqid().'.'.$this->image->extension();
            $this->image->storeAs('public',$customName);
            $product->image()->create(['url'=>$customName]);
        }

        $this->dispatch('close-modal','modalProduct');
        $this->dispatch('msg','Producto editado correctamente.');

        $this->clean();

    }

    #[On('destroyProduct')]
    public function destroy($id){
        // dump($id);
        $product = Product::findOrfail($id);

        if($product->image!=null){
            Storage::delete('public/'.$product->image->url);
            $product->image()->delete();
        }

        $product->delete();

        $this->dispatch('msg','Producto eliminado correctamente.');
    }


    // Metodo encargado de la limpieza
    public function clean(){
        $this->reset(['Id','name','image','descripcion','precio_compra','precio_venta','stock','stock_minimo','codigo_barras','fecha_vencimiento','active','category_id']);
        $this->resetErrorBag();
    }

}
