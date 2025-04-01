<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Deliverys')]
class DeliveryComponent extends Component
{
    use WithPagination;

    // Propiedades de la clase
    public $search = '';
    public $totalRegistros = 0;
    public $cant = 5;

    // Propiedades del formulario (crear/editar)
    public $name;
    public $Id;

    // Propiedades para mostrar el usuario delivery seleccionado en el header
    public $selectedDeliveryId = null;
    public $deliveryName = '';

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $this->totalRegistros = Delivery::count();
        
        $deliveries = Delivery::where('name', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);

        return view('livewire.delivery.delivery-component', [
            'deliveries' => $deliveries
        ]);
    }

    public function mount()
    {
        // Opcional: cargar delivery seleccionado inicial si fuera necesario
    }

    public function create()
    {
        $this->Id = 0;
        $this->reset(['name']);
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'modalDelivery');
    }

    // Método para crear un delivery
    public function store()
    {
        $rules = [
            'name' => 'required|min:2|max:255|unique:deliveries,name'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min'      => 'El nombre debe tener mínimo 2 caracteres',   
            'name.max'      => 'El nombre no debe superar los 255 caracteres', 
            'name.unique'   => 'El nombre ya está en uso'   
        ];

        $this->validate($rules, $messages);

        $delivery = new Delivery();
        $delivery->name = $this->name; 
        $delivery->save(); 
        
        $this->dispatch('close-modal', 'modalDelivery');
        $this->dispatch('msg', 'Delivery creado correctamente.');

        // Actualizamos el header con el nuevo delivery
        $this->selectedDeliveryId = $delivery->id;
        $this->deliveryName = $delivery->name;

        $this->reset(['name']);
    }

    public function edit(Delivery $delivery)
    {
        $this->reset(['name']);
        $this->Id = $delivery->id;
        $this->name = $delivery->name;
        $this->dispatch('open-modal', 'modalDelivery');
    }

    public function update(Delivery $delivery)
    {
        $rules = [
            'name' => 'required|min:2|max:255|unique:deliveries,name,'.$this->Id
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min'      => 'El nombre debe tener mínimo 2 caracteres',   
            'name.max'      => 'El nombre no debe superar los 255 caracteres', 
            'name.unique'   => 'El nombre ya está en uso'   
        ];

        $this->validate($rules, $messages);

        $delivery->name = $this->name;
        $delivery->update();

        $this->dispatch('close-modal', 'modalDelivery');
        $this->dispatch('msg', 'Delivery editado correctamente.');

        // Actualizamos la información del header con los datos actualizados
        $this->selectedDeliveryId = $delivery->id;
        $this->deliveryName = $delivery->name;

        $this->reset(['name']);
    }

    #[On('destroyDelivery')]
    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();

        $this->dispatch('msg', 'Delivery ha sido eliminado correctamente.');
    }
    
    // Método para actualizar el delivery seleccionado desde el select en la vista
    public function selectDelivery($id)
    {
        $delivery = Delivery::find($id);
        if ($delivery) {
            $this->selectedDeliveryId = $delivery->id;
            $this->deliveryName = $delivery->name;
        }
    }

    protected $listeners = [
        'openModalDelivery' => 'openModal'
    ];
    
    
    public function openModalRedirect()
{
    // Si tienes lógica similar a la de create, la puedes invocar aquí.
    $this->create();

    // Y si usas eventos JavaScript para abrir el modal, podrías emitir uno:
    $this->dispatchBrowserEvent('openModal', ['modalId' => 'modalDelivery']);
}

    
}
