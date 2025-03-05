<x-modal modalId="modalClient" modalTitle="Clientes">
    <form wire:submit={{$Id==0 ? "store" : "update($Id)"}}>
        <div class="form-row">

          {{-- Input Identificacion --}}
            <div class="form-group col-md-6">
                 <label for="identificacion">Codigo:</label>
                <input wire:model='identificacion' type="text" class="form-control" placeholder="Codigo" id="identificacion">
                 @error('identificacion')
                      <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                  @enderror
             </div>
            {{-- Input name --}}
            <div class="form-group col-md-6">
                <label for="name">Nombres y Apellidos:</label>
                <input wire:model='name' type="text" class="form-control" placeholder="Nombres y Apellidos" id="name">
                @error('name')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
        
            {{-- Input Email 
            <div class="form-group col-md-6">
                <label for="email">Email:</label>
                <input wire:model='email' type="email" class="form-control" placeholder="Email" id="email">
                @error('email')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>--}}
            {{-- Input Telefono --}}
            <div class="form-group col-md-6">
                <label for="telefono">Carnet de Identidad:</label>
                <input wire:model='telefono' type="text" class="form-control" placeholder="Carnet de Identidad" id="telefono">
                @error('telefono')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Empresa --}}
       {{-- Input Tipo de Cliente --}}
            <div class="form-group col-md-6">
        <label for="empresa">Tipo de Cliente:</label>
        <select wire:model='empresa' class="form-control" id="empresa">
            <option value="">Seleccione...</option>
            <option value="RECONSUMO">RECONSUMO</option>
            <option value="NUEVO">NUEVO</option>
        </select>
        @error('empresa')
            <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
        @enderror
    </div>
            {{-- Input Nit --}}
            <div class="form-group col-md-6">
                <label for="nit">Departamento:</label>
                <select wire:model="nit" class="form-control" id="nit">
                    <option value="">Seleccione un departamento</option>
                    <option value="La Paz">La Paz</option>
                    <option value="Cochabamba">Cochabamba</option>
                    <option value="Santa Cruz">Santa Cruz</option>
                    <option value="Oruro">Oruro</option>
                    <option value="Potosí">Potosí</option>
                    <option value="Chuquisaca">Chuquisaca</option>
                    <option value="Tarija">Tarija</option>
                    <option value="Beni">Beni</option>
                    <option value="Pando">Pando</option>
                </select>
            </div>

        </div>
        
        <hr>
        <button class="btn btn-primary float-right">{{$Id==0 ? 'Guardar' : 'Editar'}}</button>    
    </form>
 </x-modal>