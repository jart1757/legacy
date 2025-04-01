<?php

namespace App\Livewire\Sale;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client as Cliente;
use App\Models\Category;

class Client extends Component
{
    public $Id = 0;
    public $client = null;
    
    // Propiedades para la información del header
    public $nameClient;
    public $clientIdentificacion; // Nueva propiedad para la identificación en el header
    public $categoryName;
    
    // Propiedades del formulario (modal)
    public $name;
    public $identificacion;
    public $telefono;
    public $email;
    public $empresa;
    public $nit;
    public $category_id;

    public $categories = [];
    public $search = ''; // Para la búsqueda en tiempo real

    public function render()
    {
        // Cargamos las categorías para el formulario modal
        $this->categories = Category::all();
        
        $clients = Cliente::where(function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('identificacion', 'like', "%{$this->search}%");
        })
        ->orderBy('name')
        ->limit(10)
        ->get();

        return view('livewire.sale.client', [
            'clients' => $clients,
            'categories' => $this->categories,
        ]);
    }

    #[On('client_id')]
    public function client_id($id)
    {
        $this->client = $id;
        $findClient = Cliente::find($id);
        if ($findClient) {
            // Asignamos valores para mostrar en la tarjeta principal (header)
            $this->nameClient = $findClient->name;
            $this->clientIdentificacion = $findClient->identificacion;
            $this->categoryName = $findClient->category->name ?? 'Sin categoría';
        }
    }
    
    // Método para crear cliente
    public function store()
    {
        $rules = [
            'name'           => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients,identificacion',
            'telefono'       => 'required|max:15|unique:clients,telefono',
            'email'          => 'max:255|email|nullable',
            'category_id'    => 'required|numeric',
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

        // Actualizamos los datos del header con el nuevo cliente
        $this->nameClient = $client->name;
        $this->clientIdentificacion = $client->identificacion;
        $this->categoryName = $client->category->name ?? 'Sin categoría';

        $this->dispatch('close-modal', 'modalClient');
        $this->dispatch('msg', 'Cliente creado correctamente.');
        $this->dispatch('client_id', $client->id);

        $this->resetModal();
    }

    // Método para actualizar cliente
    public function update()
    {
        $client = Cliente::find($this->Id);
        if (!$client) {
            $this->dispatch('msg', 'Cliente no encontrado.');
            return;
        }

        $rules = [
            'name'           => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients,identificacion,' . $this->Id,
            'telefono'       => 'required|max:15|unique:clients,telefono,' . $this->Id,
            'email'          => 'max:255|email|nullable',
            'category_id'    => 'required|numeric',
        ];

        $this->validate($rules);

        $client->update([
            'name'           => $this->name,
            'identificacion' => $this->identificacion,
            'telefono'       => $this->telefono,
            'email'          => $this->email,
            'empresa'        => $this->empresa,
            'nit'            => $this->nit,
            'category_id'    => $this->category_id,
        ]);

        // Actualizamos la información del header con los datos actualizados
        $client = $client->fresh();
        $this->nameClient = $client->name;
        $this->clientIdentificacion = $client->identificacion;
        $this->categoryName = $client->category->name ?? 'Sin categoría';

        $this->dispatch('close-modal', 'modalClient');
        $this->dispatch('msg', 'Cliente actualizado correctamente.');
        $this->resetModal();
    }

    public function openModal()
    {
        // Modo crear: reseteamos el ID y los campos del modal, pero no tocamos los datos del header
        $this->Id = 0;
        $this->resetModal();
        $this->dispatch('open-modal', 'modalClient');
    }

    public function editClient()
    {
        if ($this->client) { // Aseguramos que hay un cliente seleccionado
            $client = Cliente::find($this->client);
            if ($client) {
                // Asignamos el ID para cambiar al modo edición y cargamos los datos en el formulario modal
                $this->Id = $client->id;
                $this->name = $client->name;
                $this->identificacion = $client->identificacion;
                $this->telefono = $client->telefono;
                $this->email = $client->email;
                $this->empresa = $client->empresa;
                $this->nit = $client->nit;
                $this->category_id = $client->category_id;
                
                // También actualizamos la información del header
                $this->nameClient = $client->name;
                $this->clientIdentificacion = $client->identificacion;
                $this->categoryName = $client->category->name ?? 'Sin categoría';

                $this->dispatch('open-modal', 'modalClient');
            }
        } else {
            $this->dispatch('msg', 'Debes seleccionar un cliente para editar.');
        }
    }

    // Método para resetear solo los campos del formulario modal (sin tocar los datos del header)
    public function resetModal()
    {
        $this->reset(['name', 'identificacion', 'telefono', 'email', 'empresa', 'nit', 'category_id']);
        $this->resetErrorBag();
    }
}
