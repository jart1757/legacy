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

    //Propiedades clase
    public $search='';
    public $totalRegistros=0;
    public $cant=5;

    //Propiedades modelo
    public $name;
    public $Id;

    public function render()
    {
        if($this->search!=''){
            $this->resetPage();
        }

        $this->totalRegistros = Delivery::count();
        
        $deliveries = Delivery::where('name','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate($this->cant);
        // $categories = collect();

        return view('livewire.delivery.delivery-component',[
            'deliveries' => $deliveries
        ]);
    }

    public function mount(){
        
    }

    public function create(){

        $this->Id=0;

        $this->reset(['name']);
        $this->resetErrorBag();
        $this->dispatch('open-modal','modalDelivery');
    }

    // Crear la categoria
    public function store(){
        // dump('Crear delivery');
        $rules = [
            'name' => 'required|min:2|max:255|unique:categories'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener minimo 2 caracteres',   
            'name.max' => 'El nombre no debe superar los 255 caracteres', 
            'name.unique' => 'El nombre de la categoria ya esta en uso'   
        ];

        $this->validate($rules,$messages);

        $category = new Delivery();
        $category->name = $this->name; 
        $category->save(); 
        
        $this->dispatch('close-modal','modalCategory');
        $this->dispatch('msg','Categoria creada correctamente.');

        $this->reset(['name']);
    }

    public function edit(Delivery $delivery){
        
        $this->reset(['name']);
        $this->Id = $delivery->id;
        $this->name = $delivery->name;
        $this->dispatch('open-modal','modalDelivery');


        // dump($category);
    }

    public function update(Delivery $delivery){
        // dump($delivery);
        $rules = [
            'name' => 'required|min:2|max:255|unique:categories,id,'.$this->Id
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener minimo 2 caracteres',   
            'name.max' => 'El nombre no debe superar los 255 caracteres', 
            'name.unique' => 'El nombre de la categoria ya esta en uso'   
        ];

        $this->validate($rules,$messages);

        $delivery->name = $this->name;
        $delivery->update();

        $this->dispatch('close-modal','modalDelivery');
        $this->dispatch('msg','Delivery editada correctamente.');

        $this->reset(['name']);

    }

    #[On('destroyDelivery')]
    public function destroy($id){
        // dump($id);
        $delivery = Delivery::findOrfail($id);
        $delivery->delete();

        $this->dispatch('msg','Delivery a sido eliminada correctamente.');
    }
    

}
