<?php

namespace App\Livewire\Client;

use App\Models\Client;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Clientes')]
class ClientComponent extends Component
{
    use WithPagination;

    // Propiedades de la clase
    public $search = '';
    public $totalRegistros = 0;
    public $cant = 5;

    // Propiedades del modelo
    public $Id;
    public $name;
    public $identificacion;
    public $telefono;
    public $email;
    public $empresa;
    public $nit;
    public $category_id;

    public $clients = [];
    public $selectedClientId;
    public $categories = [];
    public $client; // Mantendrá el ID del cliente seleccionado

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $this->totalRegistros = Client::count();
        $this->categories = Category::all();

        $clientes = Client::with('category')
    ->where(function ($query) {
        $query->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('identificacion', 'like', '%' . $this->search . '%');
    })
    ->orderBy('id', 'desc')
    ->paginate($this->cant);

        return view('livewire.client.client-component', [
            'clientes' => $clientes,
            'categories' => $this->categories,
        ]);
    }

    public function categories()
    {
        return Category::all();
    }

    public function updatedClient($value)
    {
        $this->selectedClientId = $value;
    }

    public function create()
    {
        $this->Id = 0;
        $this->clean();

        $this->dispatch('open-modal', 'modalClient');
    }

    // Crear cliente
    public function store()
    {
        $rules = [
            'name' => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients,identificacion',
            'telefono' => 'required|max:15|unique:clients,telefono',
            'email' => 'max:255|nullable|email',
            'category_id' => 'required|numeric',
        ];

        $this->validate($rules);

        Client::create([
            'name' => $this->name,
            'identificacion' => $this->identificacion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'empresa' => $this->empresa,
            'nit' => $this->nit,
            'category_id' => $this->category_id,
        ]);

        $this->dispatch('close-modal', 'modalClient');
        $this->dispatch('msg', 'Cliente creado correctamente.');

        $this->clean();
    }

    public function edit(Client $client)
    {
        $this->clean();

        $this->Id = $client->id;
        $this->name = $client->name;
        $this->identificacion = $client->identificacion;
        $this->telefono = $client->telefono;
        $this->email = $client->email;
        $this->empresa = $client->empresa;
        $this->nit = $client->nit;
        $this->category_id = $client->category_id;

        $this->dispatch('open-modal', 'modalClient');
    }

    public function update()
    {
        $client = Client::find($this->Id); // Buscar el cliente con el ID almacenado

        if (!$client) {
            $this->dispatch('msg', 'Cliente no encontrado.');
            return;
        }

        $rules = [
            'name' => 'required|min:5|max:255',
            'identificacion' => 'required|max:15|unique:clients,identificacion,' . $this->Id,
            'telefono' => 'required|max:15|unique:clients,telefono,' . $this->Id,
            'email' => 'max:255|nullable|email',
            'category_id' => 'required|numeric',
        ];

        $this->validate($rules);

        $client->update([
            'name' => $this->name,
            'identificacion' => $this->identificacion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'empresa' => $this->empresa,
            'nit' => $this->nit,
            'category_id' => $this->category_id,
        ]);

        $this->dispatch('close-modal', 'modalClient');
        $this->dispatch('msg', 'Cliente actualizado correctamente.');

        $this->clean();
    }

    #[On('destroyClient')]
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        $this->dispatch('msg', 'Cliente eliminado correctamente.');
    }

    public function updatedSearch()
    {
        $this->clients = Client::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('identificacion', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();
    }

    public function selectClient($clientId)
    {
        $client = Client::find($clientId);
        if ($client) {
            $this->selectedClientId = $client->id;
            $this->search = $client->name; // Mostrar el nombre seleccionado en el input
            $this->clients = []; // Limpiar la lista de sugerencias
        }
    }

    public function clean()
    {
        $this->reset(['name', 'identificacion', 'telefono', 'email', 'empresa', 'nit']);
        $this->resetErrorBag();
    }
}