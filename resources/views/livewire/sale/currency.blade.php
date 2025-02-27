<div>
    <button wire:click="openModal" class="btn bg-purple btn-xs">
        <i class="far fa-keyboard"></i>
    </button>
        
        <!-- Modal moneda -->
        <x-modal modalId="modalCurrency" modalTitle="Pago" style="color: black !importan">
        <div class="d-flex justify-content-center align-items-center flex-wrap">

           @foreach ($this->valores as $valor)

           <button wire:click="setPago({{$valor}})" type="button" class="btn btn-success m-1" {{$valor<=$total ? 'disabled': ''}}>
            {{money($valor)}}
           </button>

           @endforeach

            <button wire:click="setPago({{$total}})" type="button" class="btn btn-success m-1">Exacto</button>

        </div>
    
        </x-modal>
        {{-- End Modal --}}
</div>
