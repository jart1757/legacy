<x-modal modalId="modalClient" modalTitle="Clientes">
    <form wire:submit={{$Id==0 ? "store" : "update($Id)"}}>
        <div class="form-row">
            {{-- Input name --}}
            <div class="form-group col-md-6">
                <label for="name">Nombre:</label>
                <input wire:model='name' type="text" class="form-control" placeholder="Nombre" id="name">
                @error('name')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Identificacion --}}
            <div class="form-group col-md-6">
                <label for="identificacion">Identificacion:</label>
                <input wire:model='identificacion' type="text" class="form-control" placeholder="Identificacion" id="identificacion">
                @error('identificacion')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Email --}}
            <div class="form-group col-md-6">
                <label for="email">Email:</label>
                <input wire:model='email' type="email" class="form-control" placeholder="Email" id="email">
                @error('email')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Telefono --}}
            <div class="form-group col-md-6">
                <label for="telefono">Telefono:</label>
                <input wire:model='telefono' type="text" class="form-control" placeholder="Telefono" id="telefono">
                @error('telefono')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Empresa --}}
            <div class="form-group col-md-6">
                <label for="empresa">Empresa:</label>
                <input wire:model='empresa' type="text" class="form-control" placeholder="Empresa" id="empresa">
                @error('empresa')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Nit --}}
            <div class="form-group col-md-6">
                <label for="nit">Nit:</label>
                <input wire:model='nit' type="text" class="form-control" placeholder="Nit" id="nit">
                @error('nit')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>

        </div>
        
        <hr>
        <button class="btn btn-primary float-right">{{$Id==0 ? 'Guardar' : 'Editar'}}</button>    
    </form>
 </x-modal>