<?php

namespace App\Livewire\Sale;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client as Cliente;
use App\Models\Category;

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

    public function render()
    {
        $this->categories = Category::all(); 
        
        return view('livewire.sale.client',[
            "clients" => Cliente::all()
        ]);
    }

    public function categories(){
        return Category::all();
    }

    #[On('client_id')]
    public function client_id($id=1){
        $this->client = $id;
        $this->nameClient($id);
          // Obtener el category_id del cliente seleccionado
        $findClient = Cliente::find($id);
        $category_id = $findClient->category_id ?? null;

        // Enviar el category_id a los productos
        $this->dispatch('updateCategory', ['category_id' => $category_id]);
    }

    public function mount(){
        $this->nameClient();
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
        $this->reset(['name','identificacion','telefono','email','empresa','nit']);
        $this->resetErrorBag();
    }
}
