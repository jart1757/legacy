<x-modal modalId="modalClient" modalTitle="Clientes">
    <form wire:submit.prevent="{{$Id == 0 ? 'store' : 'update'}}">
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
            </div>
            {{-- Input Telefono --}}
            <div class="form-group col-md-6">
                <label for="telefono">Carnet de Identidad:</label>
                <input wire:model='telefono' type="text" class="form-control" placeholder="Carnet de Identidad" id="telefono">
                @error('telefono')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            {{-- Input Empresa --}}
       {{-- Input Tipo de Cliente 
            <div class="form-group col-md-6">
        <label for="empresa">Tipo de Cliente:</label>
        <select wire:model='empresa' class="form-control" id="empresa">
            <option value="">Seleccione...</option>
            <option value="CLIENTE BONIFICADO">CLIENTE BONIFICADO</option>
            <option value="PRODUCTOR MAYORISTA">PRODUCTOR MAYORISTA</option>
            <option value="CONSUMIDOR PREFERENTE">CONSUMIDOR PREFERENTE</option>
        </select>
        @error('empresa')
            <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
        @enderror
    </div>--}}
            {{-- Input Nit --}}
            <div class="form-group col-md-6">
                <label for="nit">Departamento:</label>
                <select wire:model="nit" class="form-control" id="nit">
                    <option value="">SELECCIONE UN DEPARTAMENTO</option>
                    <option value="LA PAZ">LA PAZ</option>
                    <option value="COCHABAMBA">COCHABAMBA</option>
                    <option value="SANTA CRUZ">SANTA CRUZ</option>
                    <option value="ORURO">ORURO</option>
                    <option value="POTOSÍ">POTOSÍ</option>
                    <option value="CHUQUISACA">CHUQUISACA</option>
                    <option value="TARIJA">TARIJA</option>
                    <option value="BENI">BENI</option>
                    <option value="PANDO">PANDO</option>
                </select>
            </div>
            
            {{-- Select category --}}
            <div class="form-group">
                <label for="category">Categoría:</label>
                <select wire:model="category_id" class="form-control">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            

        </div>
        
        <hr>
        <button class="btn btn-primary float-right">{{$Id==0 ? 'Guardar' : 'Editar'}}</button>    
    </form>
 </x-modal>