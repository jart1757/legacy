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
    public $cant = 10;
    public $totalRegistros = 0;

    // Propiedades de pago
    public $pago = 0;
    public $devuelve = 0;
    public $updating = 0;
    public $client = 1;
    public $category_id = null; // Se asignará desde el cliente

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
    public $searchIdentification = ''; // Nueva propiedad para buscar por identificación

       // **Casts para asegurar que ciertas propiedades sean del tipo correcto**
       protected $casts = [
        'descuento' => 'float',
        'pago'      => 'float',
        'devuelve'  => 'float',
    ];

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
                $pedidoStoredPath = $this->pedido_path->store('pedidos');
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
                $boletaStoredPath = $this->boleta_path->store('boletas');
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
            }
    
            // Limpiar el carrito
            Cart::clear();
    
            // Restablecer todos los valores del formulario
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
            $this->dispatch('resetDescuentoSelect'); // Restablecer selección de descuento
    
            // Emitir evento para refrescar la vista
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
        $product = Product::find($id);
        if (!$product) {
            return;
        }
        $productos = Product::where('name', $product->name)->get();
        foreach ($productos as $prod) {
            $prod->increment('stock');
        }
        Cart::decrement($id);
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

        $product = Product::find($id);
        if (!$product || $product->stock <= 0) {
            $this->dispatch('msg', "Stock insuficiente para {$product->name}", "danger");
            return;
        }

        $productos = Product::where('name', $product->name)->get();
        foreach ($productos as $prod) {
            $prod->decrement('stock');
        }
        Cart::increment($id);
        $this->dispatch('refreshProducts');
    }

    public function removeItem($id, $qty)
    {
        $product = Product::find($id);
        if (!$product) {
            return;
        }
        $productos = Product::where('name', $product->name)->get();
        foreach ($productos as $prod) {
            $prod->increment('stock', $qty);
        }
        Cart::removeItem($id);
        $this->dispatch('refreshProducts');
    }

    public function clear()
    {
        Cart::clear();
        $this->pago = 0;
        $this->devuelve = 0;
        $this->dispatch('msg', 'Venta cancelada');
        $this->dispatch('refreshProducts');
        $this->dispatch('resetDescuentoSelect');
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
        $query = Product::query();

        // Si se ha asignado una categoría desde el cliente, filtra por ella
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        } else if ($this->search != '') {
            // En caso contrario, si se ingresa búsqueda, filtra por nombre
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name', 'asc')
                     ->paginate($this->cant);
    }

    public function getMaxProductsByCategory()
    {
        $client = \App\Models\Client::find($this->client);

        if (!$client) {
            return 0; // Valor por defecto si no hay cliente
        }

        return match ($client->category_id) {
            1 => 5,  // Bonificado
            2 => 20, // Mayorista
            3 => 1,  // Preferente
            4 => 5,  // Reconsumo (5 cajas)
            5 => 5,
            default => 0,
        };
    }
}
