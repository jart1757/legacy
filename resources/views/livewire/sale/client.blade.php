<div>
  <div class="card card-info">
      <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-user"></i>
             <h5>Cliente:</h5>
             <span class="badge badge-success">{{$nameClient}}</span>  
             <span class="badge badge-danger">{{$clientIdentificacion}}</span> 
             <span class="badge badge-primary">{{$categoryName}}</span>
          </h3>
          <div class="card-tools">
              <button wire:click="openModal" class="btn bg-purple btn-sm">Crear cliente</button>
              <button wire:click="editClient" class="btn btn-warning btn-sm" @disabled(!$client)>
                Editar Cliente
              </button>
          </div>
      </div>
      <div class="card-body">
          <div class="form-group">
              <label>Seleccionar cliente:</label>
              <!-- Input group -->
              <div class="input-group" wire:ignore>
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <select wire:model="client" class="form-control" id="select2">
                  <option value="">Seleccione un cliente</option>
                  @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                      {{ $client->name }} - {{ $client->identificacion }}
                    </option>
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
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  @endsection

  @section('js')
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Inicializa select2
      $("#select2").select2({
        theme: "bootstrap4",
        ajax: {
          url: "{{ route('search.clients') }}", // Ruta definida en web.php
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return { search: params.term };
          },
          processResults: function(data) {
            return {
              results: $.map(data, function(client) {
                return { 
                  id: client.id, 
                  text: client.name + " - " + client.identificacion 
                };
              })
            };
          },
          cache: true
        }
      });
  
      // Evento cuando cambia el select2
      $("#select2").on('change', function() {
        Livewire.dispatch('client_id', { id: $(this).val() });
      });
  
      // Escuchar el evento de Livewire para actualizar el select2
      Livewire.on('refreshClients', function () {
        // Destruir y reiniciar select2 para actualizar los clientes
        $('#select2').select2('destroy').empty();
        // Re-inicializa select2 después de actualizar
        $("#select2").select2({
          theme: "bootstrap4",
          ajax: {
            url: "{{ route('search.clients') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
              return { search: params.term };
            },
            processResults: function(data) {
              return {
                results: $.map(data, function(client) {
                  return { 
                    id: client.id, 
                    text: client.name + " - " + client.identificacion 
                  };
                })
              };
            },
            cache: true
          }
        });
      });
    });
  </script>
  
  @endsection

</div>

