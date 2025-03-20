<?php

namespace App\Livewire\Sale;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Models\Image;

#[Title('Ventas')]
class SaleCreate extends Component
{
    use WithPagination, WithFileUploads;

    // Propiedades de la clase
    public $search = '';
    public $cant = 5;
    public $totalRegistros = 0;

    // Propiedades de pago
    public $pago = 0;
    public $devuelve = 0;
    public $updating = 0;
    public $client = 1;
    public $category_id = null; // Agrega esta línea

    // Propiedades adicionales
    public $fechaing;
    public $delivery_id;
    public $file_path;
    public $tipo;
    public $departamento;
    public $provincia;
    
    // Propiedades para subir archivos (nombres deben coincidir con el wire:model en la vista)
    public $pedido_path;
    public $boleta_path;
    
    // Agregar propiedad descuento
    public $descuento = 0; // Valor inicial del descuento

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $this->totalRegistros = Product::count();

        if ($this->updating == 0) {
            $this->pago = Cart::getTotal();
            $this->devuelve = $this->pago - $this->getTotalConDescuento();
        }

        return view('livewire.sale.sale-create', [
            'products'       => $this->products,
            'cart'           => Cart::getCart(),
            'total'          => $this->getTotalConDescuento(),
            'totalArticulos' => Cart::totalArticulos()
        ]);
    }

    #[Computed]
    public function getTotalConDescuento()
    {
        return Cart::getTotal() - $this->descuento;
    }

    public function updatedDescuento()
    {
        $this->devuelve = $this->pago - $this->getTotalConDescuento();
        $this->dispatch('$refresh');
    }

    public function createSale()
{
    $cart = Cart::getCart();

    if (count($cart) == 0) {
        $this->dispatch('msg', 'No hay productos', "danger");
        return;
    }

    if ($this->pago < $this->getTotalConDescuento()) {
        $this->pago = $this->getTotalConDescuento();
        $this->devuelve = 0;
    }

    DB::transaction(function () {
        $sale = new Sale();
        $sale->total         = $this->getTotalConDescuento();
        $sale->pago          = $this->pago;
        $sale->user_id       = userID();
        $sale->client_id     = $this->client;
        $sale->fecha         = date('Y-m-d');
        $sale->fechaing      = $this->fechaing;
        $sale->delivery_id   = $this->delivery_id;
        $sale->tipo          = $this->tipo;
        $sale->departamento  = $this->departamento;
        $sale->provincia     = $this->provincia;
        $sale->descuento     = $this->descuento;
        $sale->save();

        // Subida de imágenes
        if ($this->pedido_path) {
            $pedidoStoredPath = $this->pedido_path->store('images', 'public');
            $sale->pedido_path = $pedidoStoredPath;
            $sale->save();
            Image::create([
                'url'            => $pedidoStoredPath,
                'imageable_id'   => $sale->id,
                'imageable_type' => Sale::class,
                'type'           => 'pedido'
            ]);
        }

        if ($this->boleta_path) {
            $boletaStoredPath = $this->boleta_path->store('images', 'public');
            $sale->boleta_path = $boletaStoredPath;
            $sale->save();
            Image::create([
                'url'            => $boletaStoredPath,
                'imageable_id'   => $sale->id,
                'imageable_type' => Sale::class,
                'type'           => 'boleta'
            ]);
        }

        // Guardar los ítems de la venta
        foreach (\Cart::session(userID())->getContent() as $product) {
            $item = new Item();
            $item->name       = $product->name;
            $item->price      = $product->price;
            $item->qty        = $product->quantity;
            $item->image      = $product->associatedModel->imagen;
            $item->product_id = $product->id;
            $item->fecha      = date('Y-m-d');
            $item->save();

            $sale->items()->attach($item->id, [
                'qty'   => $product->quantity,
                'fecha' => date('Y-m-d')
            ]);

            Product::find($product->id)->decrement('stock', $product->quantity);
        }

        // Limpiar el carrito
        Cart::clear();

        // **Restablecer todos los valores del formulario**
        $this->reset([
            'pago',
            'devuelve',
            'client',
            'category_id',
            'fechaing',
            'delivery_id',
            'tipo',
            'departamento',
            'provincia',
            'descuento',
            'pedido_path',
            'boleta_path',
            'search'
        ]);

        // **Emitir evento para refrescar la vista**
        $this->dispatch('msg', 'Venta creada correctamente con imágenes', 'success', $sale->id);
        $this->dispatch('$refresh');
    });
}


    #[On('client_id')]
    public function client_id($id = 1)
    {
        $this->client = $id;
        $client = \App\Models\Client::find($id);
        $this->category_id = $client->category_id ?? null; // Asigna la categoría del cliente
        $this->dispatch('msg', 'Cliente seleccionado. Actualizando límites...', 'info');
    }

    public function updatingPago($value)
    {
        $this->updating = 1;
        $this->pago = $value;
        $this->devuelve = $this->pago - $this->getTotalConDescuento();
    }

    #[On('add-product')]
    public function addProduct(Product $product)
    {
        $this->updating = 0;
        Cart::add($product);
        $this->dispatch('refreshProducts');
    }

    public function decrement($id)
{
    // Obtener el producto específico
    $product = Product::find($id);

    if (!$product) {
        return;
    }

    // Obtener todos los productos con el mismo nombre
    $productos = Product::where('name', $product->name)->get();

    // Aumentar stock en todos los productos con el mismo nombre
    foreach ($productos as $prod) {
        $prod->increment('stock');
    }

    // Disminuir cantidad en el carrito
    Cart::decrement($id);

    // Emitir evento para actualizar la vista
    $this->dispatch('refreshProducts');
}


    public function increment($id)
    {
        $cart = Cart::getCart();
        $totalQty = $cart->sum('quantity');
        $maxQty = $this->getMaxProductsByCategory();
    
        if ($totalQty >= $maxQty) {
            $this->dispatch('msg', "No puedes agregar más productos. Límite máximo: $maxQty", "warning");
            return;
        }
    
        // Obtener el producto específico
        $product = Product::find($id);
    
        if (!$product || $product->stock <= 0) {
            $this->dispatch('msg', "Stock insuficiente para {$product->name}", "danger");
            return;
        }
    
        // Obtener todos los productos con el mismo nombre
        $productos = Product::where('name', $product->name)->get();
    
        // Restar stock a todos los productos con el mismo nombre
        foreach ($productos as $prod) {
            $prod->decrement('stock');
        }
    
        // Agregar al carrito
        Cart::increment($id);
    
        // Emitir evento para actualizar la vista
        $this->dispatch('refreshProducts');
    }
    

    public function removeItem($id, $qty)
    {
        // Obtener el producto específico
        $product = Product::find($id);
    
        if (!$product) {
            return;
        }
    
        // Obtener todos los productos con el mismo nombre
        $productos = Product::where('name', $product->name)->get();
    
        // Devolver stock a todos los productos con el mismo nombre
        foreach ($productos as $prod) {
            $prod->increment('stock', $qty);
        }
    
        // Remover del carrito
        Cart::removeItem($id);
    
        // Emitir evento para actualizar la vista
        $this->dispatch('refreshProducts');
    }
    

    public function clear()
    {
        Cart::clear();
        $this->pago = 0;
        $this->devuelve = 0;
        $this->dispatch('msg', 'Venta cancelada');
        $this->dispatch('refreshProducts');
    }

    #[On('setPago')]
    public function setPago($valor)
    {
        $this->updating = 1;
        $this->pago = $valor;
        $this->devuelve = $this->pago - $this->getTotalConDescuento();
    }

    #[Computed]
    public function products()
    {
        return Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);
    }

    public function getMaxProductsByCategory()
    {
        $client = \App\Models\Client::find($this->client);

    if (!$client) {
        return 0; // Si no hay cliente, retorna un valor por defecto
    }

    return match ($client->category_id) {
        1 => 5,  // Bonificado
        2 => 20, // Mayorista
        3 => 1,  // Preferente
        4 => 5,  // Reconsumo (5 cajas)
        default => 0,
    };
    }
    
    #[Computed]
    public function getProducts()
    {
        $query = \App\Models\Product::query();

        // Filtra por nombre si se ha ingresado una búsqueda
        if ($this->search != '') {
        $query->where('name', 'like', '%' . $this->search . '%');
    }
    
        // Filtrar por la categoría del cliente seleccionado
        if ($this->category_id) {
        $query->where('category_id', $this->category_id);
    }

    return $query->paginate(19);
}

}
