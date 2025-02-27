<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Ver usuario')]
class UserShow extends Component
{
    use WithPagination;
    
    public User $user;
    
    public function render()
    {
        $sales = $this->user->sales()->paginate(5);

        return view('livewire.user.user-show',compact('sales'));
    }
}
