<div>

   {{-- @dd($cart) --}}
    <x-card cardTitle="Editar venta">
       <x-slot:cardTools>

          <a href="{{route('sales.list')}}" class="btn btn-primary btn-sm mr-2">
            <i class="fas fa-shopping-cart"></i> Ver Salidas
          </a>

          <a href="#" class="btn btn-sm btn-danger {{isset($sale) ? 'disabled' : ''}}" wire:click='clear'>
            <i class="fas fa-trash"></i> Crear Salidas
          </a>

       </x-slot>

      {{-- CONTENT --}}
       <div class="row">
         {{-- COLUMNA DETALLES VENTA --}}
               {{-- COLUMNA DETALLES VENTA --}}
               <div class="col-md-6">
                  {{-- Card details --}}
                  @include('sales.card-details')
                  {{-- Card pago --}}
                  @include('sales.card-pago')
               
         
         
               </div>
      
               <div class="col-md-6">
     
                              {{-- COLUMNA PRODUCTOS --}}
                  @include('sales.list-products')
               </div>

       </div>

 
       <x-slot:cardFooter>
            
       </x-slot>
    </x-card>

</div>
