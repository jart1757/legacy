<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Ver cliente')]
class ClientShow extends Component
{
    use WithPagination;
    
    public Client $client;

    public function render()
    {
        $sales = $this->client->sales()->paginate(5);

        return view('livewire.client.client-show',compact('sales'));
    }
}
