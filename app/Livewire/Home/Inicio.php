<?php

namespace App\Livewire\Home;

use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Delivery;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Inicio')]
class Inicio extends Component
{
    // Ventas hoy
    public $ventasHoy = 0;
    public $totalventasHoy = 0;
    public $articulosHoy = 0;
    public $productosHoy = 0;

    // Ventas mes grafica
    public $listTotalVentasMes = '';

    // Cajas reportes
    public $cantidadVentas = 0;
    public $totalventas = 0;
    public $cantidadArticulos = 0;
    public $cantidadProductos = 0;

    public $cantidadProducts = 0;
    public $cantidadStock = 0;
    public $cantidadCategories = 0;
    public $cantidadClients = 0;

    // Productos mas vendidos y recientes
    public $productosMasVendidosHoy = 0;
    public $productosMasVendidosMes = 0;
    public $productosMasVendidos = 0;
    public $productosRecientes = 0;

    // Propiedades mejores vendedores y compradores
    public $bestSellers = 0;
    public $bestBuyers = 0;

    //para regiones la grafica 
    public $salesByRegion = [];

    public function render()
    {
        $this->sales_today();
        $this->calVentasMes();
        $this->boxes_reports();
        $this->set_products_reports();
        $this->set_best_sellers_buyers();
        $this->getSalesByRegion(); // Carga ventas por región

        return view('livewire.home.inicio');
    }
    // Para calcular la región
    public function getSalesByRegion()
    {
       $this->salesByRegion = DB::table('sales as s')
           ->join('item_sale as is', 's.id', '=', 'is.sale_id')
          ->select(
             's.departamento',
              's.provincia',
              DB::raw('SUM(is.qty) as total_stock') // Eliminado DISTINCT, ya que no es necesario
           )
           ->groupBy('s.departamento', 's.provincia')
           ->orderBy('total_stock', 'desc')
            ->get()
           ->toArray();
    }

    
    
    

    public function set_best_sellers_buyers()
    {
        $this->bestSellers = $this->best_sellers();
        $this->bestBuyers = $this->best_buyers();
    }

    public function best_buyers()
    {
        return Delivery::select('deliveries.id', 'deliveries.name', DB::raw('SUM(sales.total) as total'))
            ->join('sales', 'sales.delivery_id', '=', 'deliveries.id')
            ->whereYear('sales.fecha', date("Y"))
            ->groupBy('deliveries.id', 'deliveries.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    public function best_sellers()
    {
        return User::select('users.id', 'users.name', 'users.admin', DB::raw('SUM(sales.total) as total'))
            ->join('sales', 'sales.user_id', '=', 'users.id')
            ->whereYear('sales.fecha', date("Y"))
            ->groupBy('users.id', 'users.name', 'users.admin')  // Agregué 'users.admin' aquí
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }
    

    // Cargar propiedades productos más vendidos
    public function set_products_reports()
    {
        $this->productosMasVendidosHoy = $this->products_reports(1);
        $this->productosMasVendidosMes = $this->products_reports(0, 1);
        $this->productosMasVendidos = $this->products_reports();
        $this->productosRecientes = Product::take(5)
            ->orderBy('id', 'desc')
            ->get();
    }

    // Consulta productos más vendidos
    public function products_reports($filtraDia = 0, $filtrarMes = 0)
{
    $productsQuery = Item::select(
        'items.name',            // Eliminé 'items.id'
        'items.price',
        'items.image',
        'items.product_id',
        DB::raw('SUM(items.qty) as total_quantity')
    )
    ->whereYear('items.fecha', date("Y"))
    ->groupBy('items.product_id', 'items.name', 'items.price', 'items.image');  // No es necesario incluir 'items.id'

    if ($filtraDia) {
        $productsQuery = $productsQuery->whereDate('items.fecha', date('Y-m-d'));
    }

    if ($filtrarMes) {
        $productsQuery = $productsQuery->whereMonth('items.fecha', date('m'));
    }

    return $productsQuery->orderByDesc('total_quantity')->take(5)->get();
}


    public function sales_today()
    {
        $this->ventasHoy = Sale::whereDate('fecha', date('Y-m-d'))->count();
        $this->totalventasHoy = Sale::whereDate('fecha', date('Y-m-d'))->sum('total');
        $this->articulosHoy = Item::whereDate('fecha', date('Y-m-d'))->sum('qty');
        $this->productosHoy = Item::whereYear('fecha', 2025)
            ->groupBy('product_id')  // Eliminé 'distinct' y corregí el uso de groupBy
            ->count('product_id');
    }

    public function calVentasMes()
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->listTotalVentasMes .= Sale::whereMonth('fecha', '=', $i)->sum('total') . ',';
        }
    }

    public function boxes_reports()
    {
        $this->cantidadVentas = Sale::whereYear('fecha', '=', date('Y'))->count();
        $this->totalventas = Sale::whereYear('fecha', '=', date('Y'))->sum('total');
        $this->cantidadArticulos = Item::whereYear('fecha', '=', date('Y'))->sum('qty');
        $this->cantidadProductos = Item::whereYear('fecha', '=', date('Y'))
            ->groupBy('product_id')
            ->count('product_id');

        $this->cantidadProducts = Product::count();
        $this->cantidadStock = Product::sum('stock');
        $this->cantidadCategories = Category::count();
        $this->cantidadClients = Client::count();
    }
}
