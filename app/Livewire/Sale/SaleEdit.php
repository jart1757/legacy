<?php

namespace App\Livewire\Sale;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Delivery;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

#[Title('Ventas')]
class SaleEdit extends Component
{
    use WithPagination, WithFileUploads;

    // Propiedades de la clase
    public $search = '';
    public $cant = 5;
    public $totalRegistros = 0;
    public Sale $sale;
    public $cart;
    public $loadCart = false;
    public $client;
    public $fechaing;
    public $delivery_id;
    public $extra;
    public $descuento;
    public $pedido_path;
    public $boleta_path;
    public $departamento;
    public $provincia;

    public function mount()
    {
        $this->client = $this->sale->client_id ?? null;
        $this->fechaing = $this->sale->fechaing ?? now()->format('Y-m-d');
        $this->delivery_id = $this->sale->delivery_id ?? '';
        $this->extra = $this->sale->extra ?? 0;
        $this->descuento = $this->sale->descuento ?? 0;
        $this->departamento = $this->sale->departamento ?? '';
        $this->provincia = $this->sale->provincia ?? '';
    }

    public function render()
    {
        if (!$this->loadCart) {
            $this->getItemsToCart();
        } else {
            $this->cart = Cart::getCart();
        }

        return view('livewire.sale.sale-edit', [
            'totalArticulos' => Cart::totalArticulos(),
            'total' => Cart::getTotal(),
            'products' => $this->products,
            'deliveries' => Delivery::all()
        ]);
    }

    public function editSale()
    {
        // Actualizar datos de la venta
        $this->sale->total = $this->getTotalConDescuento();
        $this->sale->pago = $this->sale->total;
        $this->sale->fechaing = $this->fechaing;
        $this->sale->delivery_id = $this->delivery_id;
        $this->sale->extra = $this->extra;
        $this->sale->descuento = $this->descuento;
        $this->sale->departamento = $this->departamento;
        $this->sale->provincia = $this->provincia;
    
        if ($this->pedido_path) {
            $this->sale->pedido_path = $this->pedido_path->store('pedidos');
        }
    
        if ($this->boleta_path) {
            $this->sale->boleta_path = $this->boleta_path->store('boletas');
        }
    
        $this->sale->update();
    
        // === Revertir el stock de la venta anterior de forma agrupada ===
        $revertGroups = [];
        foreach ($this->sale->items as $item) {
            // Agrupar cantidades por nombre del producto
            if (isset($revertGroups[$item->name])) {
                $revertGroups[$item->name] += $item->qty;
            } else {
                $revertGroups[$item->name] = $item->qty;
            }
            // Eliminar el ítem (para luego re-asociarlo con los nuevos)
            $item->delete();
        }
        // Revertir stock de todos los productos agrupados por nombre
        foreach ($revertGroups as $name => $totalQty) {
            Product::where('name', $name)->increment('stock', $totalQty);
        }
    
        // === Agregar los nuevos ítems de la venta y descontar stock de forma agrupada ===
        $itemsIds = [];
        $deductions = [];
        foreach (Cart::getCart() as $product) {
            $item = new Item();
            $item->name       = $product->name;
            $item->price      = $product->price;
            $item->qty        = $product->quantity;
            $item->image      = $product->associatedModel->imagen;
            $item->product_id = $product->id;
            $item->fecha      = date('Y-m-d');
            $item->save();
    
            $this->sale->items()->attach($item->id, [
                'qty'   => $product->quantity,
                'fecha' => date('Y-m-d')
            ]);
    
            // Agrupar la cantidad a descontar por nombre
            if (isset($deductions[$product->name])) {
                $deductions[$product->name] += $product->quantity;
            } else {
                $deductions[$product->name] = $product->quantity;
            }
            $itemsIds[$item->id] = ['qty' => $product->quantity, 'fecha' => date('Y-m-d')];
        }
        // Descontar stock de los nuevos ítems de forma agrupada
        foreach ($deductions as $name => $totalQty) {
            Product::where('name', $name)->decrement('stock', $totalQty);
        }
    
        $this->sale->items()->sync($itemsIds);
        $this->dispatch('msg', 'Venta editada correctamente', 'success', $this->sale->id);
        
        // Limpiar el carrito y restablecer los formularios
        $this->clearCartAndResetForm();
    }
    


    public function getItemsToCart()
    {
        foreach ($this->sale->items as $item) {
            $product = Product::find($item->product_id);
            $existingItem = \Cart::session(userID())->get($item->product_id);

            if (!$existingItem) {
                \Cart::session(userID())->add([
                    'id' => $item->product_id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'attributes' => [],
                    'associatedModel' => $product,
                ]);
            }
        }
        $this->loadCart = true;
        $this->cart = Cart::getCart();
    }

    #[On('add-product')]
    public function addProduct(Product $product)
    {
        Cart::add($product);
    }

    public function decrement($id)
{
    $product = Product::find($id);

    if (!$product) {
        return;
    }

    // Obtener todos los productos con el mismo nombre
    $productos = Product::where('name', $product->name)->get();

    // Incrementar el stock de todos los productos con el mismo nombre
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

    // Aumentar cantidad en el carrito
    Cart::increment($id);

    // Emitir evento para actualizar la vista
    $this->dispatch('refreshProducts');
}

public function removeItem($id, $qty)
{
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

    // Eliminar del carrito
    Cart::removeItem($id);

    // Emitir evento para actualizar la vista
    $this->dispatch('refreshProducts');
}

    #[Computed()]
    public function products()
    {
        return Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);
    }
    #[On('clientSelected')]
    public function updateClient($data)
    {
        $this->client = $data['id'];
    }
    #[Computed]
    public function getTotalConDescuento()
    {
        return Cart::getTotal() - $this->descuento;
    }

    public function updatedDescuento()
    {
        $this->dispatch('$refresh');
    }

    public function clearCart()
    {
        \Cart::session(userID())->clear(); // Limpia todos los productos del carrito
        $this->cart = []; // Actualiza la propiedad en Livewire
        $this->dispatch('$refresh'); // Refresca la vista
    }

    public function clearCartAndResetForm()
{
    // Limpiar el carrito
    \Cart::session(userID())->clear();
    $this->cart = []; // Actualiza la propiedad del carrito en Livewire
    
    // Restablecer valores de los formularios
    $this->search = '';
    $this->cant = 5;
    $this->totalRegistros = 0;
    $this->client = null;
    $this->fechaing = now()->format('Y-m-d');
    $this->delivery_id = '';
    $this->extra = 0;
    $this->descuento = 0;
    $this->pedido_path = null;
    $this->boleta_path = null;
    $this->departamento = '';
    $this->provincia = '';
    
    // Refrescar la vista
    $this->dispatch('$refresh');
}




    //editar clientes con su cantidad
    public function getMaxProductsByCategory()
    {
        $client = $this->sale->client ?? null;
    
        if (!$client) {
            return 0; // Si no hay cliente, devolvemos 0 por defecto
        }
    
        return match ($client->category_id) {
            1 => 5,  // Bonificado
            2 => 20, // Mayorista
            3 => 1,  // Preferente
            4 => 5,  // Reconsumo (5 cajas)
            default => 0,
        };
    }

}
