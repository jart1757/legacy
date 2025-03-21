<?php

namespace App\Livewire\Sale;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client as Cliente;
use App\Models\Category;
use Illuminate\Http\Request;

class Client extends Component
{
    public $Id=0;
    public $client=1;
    public $nameClient;

    //Propiedades modelo
    public $name;
    public $identificacion;
    public $telefono;
    public $email;
    public $empresa;
    public $nit;
    public $category_id;

    public $categories = [];
    public $selectedClientId = null;
    public $categoryName;
    public $search = ''; // Para la búsqueda en tiempo real


    


    public function render()
    {
        $clients = Cliente::where('name', 'like', "%{$this->search}%")
        ->orWhere('identificacion', 'like', "%{$this->search}%")
        ->orderBy('name')
        ->limit(500)
        ->get();

        return view('livewire.sale.client', compact('clients'));
    }

    public function categories(){
        return Category::all();
    }
    #[On('client_id')]
    public function client_id($id)
    {
        $this->client = $id;
        $findClient = Cliente::find($id);
        $this->nameClient = $findClient->name;
        $this->identificacion = $findClient->identificacion; // Aquí asignamos la identificación
        $this->categoryName = $findClient->category->name ?? 'Sin categoría';

        $this->dispatch('updateCategory', ['category_id' => $findClient->category_id ?? null]);
    }
    
    public function search(Request $request)
{
    $search = $request->search;

    $clients = Client::where('name', 'like', "%{$search}%")
        ->orWhere('identificacion', 'like', "%{$search}%")
        ->orderBy('name')
        ->limit(10)
        ->get();

    return response()->json($clients);
}
    
    public function mount($client = null)
{
    if ($client) {
        $this->isEditing = true;
        $this->client = $client;
        $this->name = $client->name;
       
    }
}

    public function nameClient($id=1){
        $findClient = Cliente::find($id);
        $this->nameClient = $findClient->name;
    }

    // Crear cliente
    public function store(){
        
        $rules = [
            'name' => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients',
            'email' => 'max:255|email|nullable',
            'category_id' => 'required|numeric',
        ];


        $this->validate($rules);

        $client = new Cliente();
        $client->name = $this->name; 
        $client->identificacion = $this->identificacion;
        $client->telefono = $this->telefono; 
        $client->email = $this->email; 
        $client->empresa = $this->empresa; 
        $client->nit = $this->nit; 
        $client->category_id = $this->category_id; 

        $client->save(); 
        
        $this->dispatch('close-modal','modalClient');
        $this->dispatch('msg','Cliente creado correctamente.');

        $this->dispatch('client_id',$client->id);

        $this->clean();
    }

    public function openModal()
    {
        $this->dispatch('open-modal','modalClient');
    }

    public function clean(){
        $this->reset(['name','identificacion','telefono','email','empresa','nit','category_id']);
        $this->resetErrorBag();
    }
    public function editClient()
    {
        if ($this->client) { // Asegurar que hay un cliente seleccionado
            $client = Cliente::find($this->client); // Usar Cliente en lugar de Client
            if ($client) {
                // Asignar los valores del cliente a las propiedades
                $this->selectedClientId = $client->id;
                $this->name = $client->name;
                $this->identificacion = $client->identificacion;
                $this->telefono = $client->telefono;
                $this->email = $client->email;
                $this->empresa = $client->empresa;
                $this->nit = $client->nit;
                $this->category_id = $client->category_id;
    
                // Abrir modal de edición
                $this->dispatch('open-modal', 'modalClient');
            }
        } else {
            $this->dispatch('msg', 'Debes seleccionar un cliente para editar.');
        }
    }
    
    
}
