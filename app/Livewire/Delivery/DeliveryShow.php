<?php

namespace App\Livewire\Delivery;

use Livewire\Component;
use WithPagination;

class DeliveryShow extends Component
{
    public $deliveries;
  
    public function render()
    {
        return view('livewire.delivery.delivery-show');
    }
}
