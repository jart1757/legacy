<div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-user"></i>
               Cliente: <span class="badge badge-secondary">{{$nameClient}}</span> 
            </h3>
            <div class="card-tools">
                <button wire:click="openModal" class="btn bg-purple btn-sm">Crear cliente</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Seleccionar cliente:</label>

                <!--input group -->
                <div class="input-group" wire:ignore>
                  <div class="input-group-prepend" >
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                
                  <select wire:model.live='client' class="form-control" id="select2">

                    @foreach ($clients as $client)
                     <option value="{{$client->id}}">{{$client->name}}</option>
                    @endforeach
                     
                  </select>

                </div>
                <!-- /.input group -->
              </div>
        </div>
      </div>
    <!-- Modal Cliente -->
      @include('clients.form')
    {{-- End Modal --}}

@section('styles')
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('js')
  <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

  <script>

    $("#select2").select2({
      theme:"bootstrap4"
    });

    $("#select2").on('change', function(){
      Livewire.dispatch('client_id',{id: $(this).val()})
    })

  </script>

@endsection

</div>

