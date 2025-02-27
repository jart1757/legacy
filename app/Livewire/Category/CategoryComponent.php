<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Categorias')]
class CategoryComponent extends Component
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

        $this->totalRegistros = Category::count();
        
        $categories = Category::where('name','like','%'.$this->search.'%')
            ->orderBy('id','desc')
            ->paginate($this->cant);
        // $categories = collect();

        return view('livewire.category.category-component',[
            'categories' => $categories
        ]);
    }

    public function mount(){
        
    }

    public function create(){

        $this->Id=0;

        $this->reset(['name']);
        $this->resetErrorBag();
        $this->dispatch('open-modal','modalCategory');
    }

    // Crear la categoria
    public function store(){
        // dump('Crear category');
        $rules = [
            'name' => 'required|min:5|max:255|unique:categories'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener minimo 5 caracteres',   
            'name.max' => 'El nombre no debe superar los 255 caracteres', 
            'name.unique' => 'El nombre de la categoria ya esta en uso'   
        ];

        $this->validate($rules,$messages);

        $category = new Category();
        $category->name = $this->name; 
        $category->save(); 
        
        $this->dispatch('close-modal','modalCategory');
        $this->dispatch('msg','Categoria creada correctamente.');

        $this->reset(['name']);
    }

    public function edit(Category $category){
        
        $this->reset(['name']);
        $this->Id = $category->id;
        $this->name = $category->name;
        $this->dispatch('open-modal','modalCategory');


        // dump($category);
    }

    public function update(Category $category){
        // dump($category);
        $rules = [
            'name' => 'required|min:5|max:255|unique:categories,id,'.$this->Id
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener minimo 5 caracteres',   
            'name.max' => 'El nombre no debe superar los 255 caracteres', 
            'name.unique' => 'El nombre de la categoria ya esta en uso'   
        ];

        $this->validate($rules,$messages);

        $category->name = $this->name;
        $category->update();

        $this->dispatch('close-modal','modalCategory');
        $this->dispatch('msg','Categoria editada correctamente.');

        $this->reset(['name']);

    }

    #[On('destroyCategory')]
    public function destroy($id){
        // dump($id);
        $category = Category::findOrfail($id);
        $category->delete();

        $this->dispatch('msg','Categoria a sido eliminada correctamente.');
    }
    


}
