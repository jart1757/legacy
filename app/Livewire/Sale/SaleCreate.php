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

#[Title('Ventas')]
class SaleCreate extends Component
{
    use WithPagination;

    //Propiedades clase
    public $search='';
    public $cant=5;
    public $totalRegistros=0;
    //Propiedades pago
    public $pago=0;
    public $devuelve=0;
    public $updating=0;

    public $client=1;

    public function render()
    {
        if($this->search!=''){
            $this->resetPage();
        }
        
        $this->totalRegistros = Product::count();

        if($this->updating==0){
            $this->pago = Cart::getTotal();
            $this->devuelve = $this->pago - Cart::getTotal();
        }


        return view('livewire.sale.sale-create',[
            'products' => $this->products,
            'cart' => Cart::getCart(),
            'total' => Cart::getTotal(),
            'totalArticulos' => Cart::totalArticulos()
        ]);
    }

    // Crear venta
    public function createSale(){

        $cart = Cart::getCart();

        if(count($cart)==0){
            $this->dispatch('msg','No hay productos',"danger");
            return;
            // dump(count($cart));
        }

        if($this->pago<Cart::getTotal()){
            $this->pago = Cart::getTotal();
            $this->devuelve=0;
        }

        DB::transaction(function () {
            $sale = new Sale();
            $sale->total = Cart::getTotal();
            $sale->pago = $this->pago;
            $sale->user_id = userID();
            $sale->client_id = $this->client;
            $sale->fecha = date('Y-m-d');
            $sale->save();

            // global $cart;

            //agregar items a la venta
            foreach(\Cart::session(userID())->getContent() as $product){
                $item = new Item();
                $item->name = $product->name;
                $item->price = $product->price;
                $item->qty = $product->quantity;
                $item->image = $product->associatedModel->imagen;
                $item->product_id = $product->id;
                $item->fecha = date('Y-m-d');
                $item->save();

                $sale->items()->attach($item->id,['qty'=>$product->quantity,'fecha'=>date('Y-m-d')]);

                Product::find($product->id)->decrement('stock',$product->quantity);

            }

            Cart::clear();
            $this->reset(['pago','devuelve','client']);
            $this->dispatch('msg','Venta creada correctamente','success',$sale->id);
        });
        
    }

    // Eschuchar evento para establecer id de cliente
    #[On('client_id')]
    public function client_id($id=1){
        $this->client = $id;
    }

    // detectar cuando se edite el input pago
    public function updatingPago($value){
        $this->updating=1;
        $this->pago = $value;
        $this->devuelve = (int)$this->pago - Cart::getTotal();
    }
    
    // Agregar producto al carrito
    #[On('add-product')]
    public function addProduct(Product $product){
        $this->updating=0;
        //dump($product);
        Cart::add($product);
    }

    // Decrementar cantidad
    public function decrement($id){
        $this->updating=0;
        Cart::decrement($id);
        $this->dispatch("incrementStock.{$id}");
    }

    // Incrementar cantidad
    public function increment($id){
        $this->updating=0;
        Cart::increment($id);
        $this->dispatch("decrementStock.{$id}");
    }

    // Eliminar item del carrito
    public function removeItem($id,$qty){
        $this->updating=0;
        Cart::removeItem($id);
        $this->dispatch("devolverStock.{$id}",$qty);
    }
   
    // Cancelar venta
    public function clear(){
        Cart::clear();
        $this->pago=0;
        $this->devuelve=0;
        $this->dispatch('msg','Venta cancelada');
        $this->dispatch('refreshProducts');
        
    }

    // Recibir valor del pago desde currency
    #[On('setPago')]
    public function setPago($valor){
        $this->updating=1;
        $this->pago = $valor;
        $this->devuelve = $this->pago-Cart::getTotal();
        
    }

    // Propiedad para obtener listado productos
    #[Computed()]
    public function products(){
        return Product::where('name','like','%'.$this->search.'%')
        ->orderBy('id','desc')
        ->paginate($this->cant);
    }
}
