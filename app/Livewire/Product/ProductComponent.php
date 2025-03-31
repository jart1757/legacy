<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

#[Title('Productos')]
class ProductComponent extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $totalRegistros = 0;
    public $cant = 10;
    
    // Propiedades del modelo
    public $Id = 0;
    public $name;
    public $category_id;
    public $descripcion;
    public $precio_compra;
    public $precio_venta;
    public $codigo_barras;
    public $stock = 0;
    public $stock_minimo = 100;
    public $fecha_vencimiento;
    public $active = 1;
    public $image;
    public $imageModel;
    public $categories;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->totalRegistros = Product::count();
        $this->categories=Category::all();

        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        return view('livewire.product.product-component', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }

    public function create()
    {
        $this->Id = 0;
        $this->clean();
        $this->dispatch('open-modal', 'modalProduct');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:2|max:255',
            'descripcion' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ]);

        $product = Product::create([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'codigo_barras' => $this->codigo_barras,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'category_id' => $this->category_id,
            'active' => $this->active
        ]);

        if ($this->image) {
            $customName = 'products/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $product->image()->create(['url' => $customName]);
        }

        $this->syncStock($this->name, $this->stock);

        $this->dispatch('close-modal', 'modalProduct');
        $this->dispatch('msg', 'Producto creado correctamente.');
        $this->clean();
    }

    public function update(Product $product)
    {
        $this->validate([
            'name' => 'required|min:2|max:255',
            'descripcion' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ]);

        $product->update([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'codigo_barras' => $this->codigo_barras,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'category_id' => $this->category_id,
            'active' => $this->active
        ]);

        if ($this->image) {
            if ($product->image) {
                Storage::delete('public/' . $product->image->url);
                $product->image()->delete();
            }
            $customName = 'products/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $product->image()->create(['url' => $customName]);
        }

        $this->syncStock($this->name, $this->stock);

        $this->dispatch('close-modal', 'modalProduct');
        $this->dispatch('msg', 'Producto editado correctamente.');
        $this->clean();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::delete('public/' . $product->image->url);
            $product->image()->delete();
        }
        $product->delete();
        $this->dispatch('msg', 'Producto eliminado correctamente.');
    }

    private function syncStock($name, $stock)
    {
        Product::where('name', $name)->update(['stock' => $stock]);
    }

    public function clean()
    {
        $this->reset(['Id', 'name', 'image', 'descripcion', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'codigo_barras', 'fecha_vencimiento', 'active', 'category_id']);
        $this->resetErrorBag();
    }
}
