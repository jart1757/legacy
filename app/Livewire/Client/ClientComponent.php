<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Clientes')]
class ClientComponent extends Component
{
    use WithPagination;

    //Propiedades clase
    public $search='';
    public $totalRegistros=0;
    public $cant=5;

    //Propiedades modelo
    public $Id;
    public $name;
    public $identificacion;
    public $telefono;
    public $email;
    public $empresa;
    public $nit;
    
    public function render()
    {
        if($this->search!=''){
            $this->resetPage();
        }

        $this->totalRegistros = Client::count();
        
        $clientes = Client::where('name','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate($this->cant);
       
        return view('livewire.client.client-component',[
            'clientes' => $clientes
        ]);
    }

    public function create(){

        $this->Id=0;
        $this->clean();

        $this->dispatch('open-modal','modalClient');
    }

    // Crear cliente
    public function store(){
        
        $rules = [
            'name' => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients',
            'email' => 'max:255|email|nullable'
        ];


        $this->validate($rules);

        $client = new Client();
        $client->name = $this->name; 
        $client->identificacion = $this->identificacion;
        $client->telefono = $this->telefono; 
        $client->email = $this->email; 
        $client->empresa = $this->empresa; 
        $client->nit = $this->nit; 

        $client->save(); 
        
        $this->dispatch('close-modal','modalClient');
        $this->dispatch('msg','Cliente creado correctamente.');

        $this->clean();
    }

    public function edit(Client $client){
        
        $this->clean();

        $this->Id = $client->id;
        $this->name = $client->name;
        $this->identificacion = $client->identificacion;
        $this->telefono = $client->telefono;
        $this->email = $client->email;
        $this->empresa = $client->empresa;
        $this->nit = $client->nit;

        $this->dispatch('open-modal','modalClient');

    }

    public function update(Client $client){
        
        $rules = [
            'name' => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients,id,'.$this->Id,
            'email' => 'max:255|email|nullable'
        ];

        $this->validate($rules);

        $client->name = $this->name;
        $client->identificacion = $this->identificacion;
        $client->telefono = $this->telefono;
        $client->email = $this->email;
        $client->empresa = $this->empresa;
        $client->nit = $this->nit;

        $client->update();

        $this->dispatch('close-modal','modalClient');
        $this->dispatch('msg','Cliente editado correctamente.');

        $this->clean();

    }
    
    #[On('destroyClient')]
    public function destroy($id){
        
        $client = Client::findOrfail($id);
        $client->delete();

        $this->dispatch('msg','Cliente eliminado correctamente.');
    }
    


    public function clean(){
        $this->reset(['name','identificacion','telefono','email','empresa','nit']);
        $this->resetErrorBag();
    }
}
