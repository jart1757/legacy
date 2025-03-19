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
        $this->dispatch('updateSelect2', ['client' => $this->client]);
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

        $itemsIds = [];
        foreach ($this->sale->items as $item) {
            Product::find($item->product_id)->increment('stock', $item->qty);
            $item->delete();
        }

        foreach (Cart::getCart() as $product) {
            $item = new Item();
            $item->name = $product->name;
            $item->price = $product->price;
            $item->qty = $product->quantity;
            $item->image = $product->associatedModel->imagen;
            $item->product_id = $product->id;
            $item->fecha = date('Y-m-d');
            $item->save();

            Product::find($item->product_id)->decrement('stock', $item->qty);
            $itemsIds[$item->id] = ['qty' => $product->quantity, 'fecha' => date('Y-m-d')];
        }

        $this->sale->items()->sync($itemsIds);
        $this->dispatch('msg', 'Venta editada correctamente', 'success', $this->sale->id);
        $this->clearCart(); // Llamar a la función para vaciar el carrito
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
        Cart::decrement($id);
        $this->dispatch("incrementStock.{$id}");
    }

    public function increment($id)
    {
        Cart::increment($id);
        $this->dispatch("decrementStock.{$id}");
    }

    public function removeItem($id, $qty)
    {
        Cart::removeItem($id);
        $this->dispatch("devolverStock.{$id}", $qty);
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
