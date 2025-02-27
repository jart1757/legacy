<div>
    <x-card cardTitle="Datos tienda">
       <x-slot:cardTools>
          <a class="btn btn-primary" wire:click='edit'>
            <i class="fas fa-edit"></i> Editar
          </a>
       </x-slot>
       <div class="table-responsive">
       <table class="table table-hover table-striped text-center">
        <thead>
             <th>ID</th>
             <th>
                <i class="fas fa-image"></i>
            </th>
             <th>Nombre</th>
             <th>Slogan</th>
             <th>Telefono</th>
             <th>Email</th>
             <th>Direccion</th>
             <th>Ciudad</th>
 
        </thead>
        <tbody>

             <tr>
                <td>{{$shop->id}}</td>
                <td>
                    <x-image :item="$shop" />
                    
                </td>
                <td>{{$shop->name}}</td>
                <td>{{$shop->slogan}}</td>
                <td>{{$shop->telefono}}</td>
                <td>{{$shop->email}}</td>
                <td>{{$shop->direccion}}</td>
                <td>{{$shop->ciudad}}</td>

             </tr>

            </tbody>
        </table>
    </div>
    </x-card>


{{-- MODAL EDITAR --}}
<x-modal modalId="modalShop" modalTitle="Datos tienda" modalSize="modal-lg">
    <form wire:submit="update">

        <div class="form-row">

            {{-- Input Name --}}
            <div class="form-group col-md-5">
                <label for="name">Nombre:</label>
                <input wire:model='name' type="text" class="form-control" placeholder="Nombre tienda" id="name">
                @error('name')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>

            {{-- Input Slogan --}}
            <div class="form-group col-md-7">
                <label for="slogan">Slogan:</label>
                <input wire:model='slogan' type="text" class="form-control" placeholder="Slogan tienda" id="slogan">
                @error('slogan')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>


            {{-- Input Telefono --}}
            <div class="form-group col-md-5">
                <label for="telefono">Telefono:</label>
                <input wire:model='telefono' type="text" class="form-control" placeholder="Telefono tienda" id="telefono">
                @error('telefono')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>


            {{-- Input Telefono --}}
            <div class="form-group col-md-7">
                <label for="email">Email:</label>
                <input wire:model='email' type="email" class="form-control" placeholder="Email tienda" id="email">
                @error('email')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>

            {{-- Input Direccion --}}
            <div class="form-group col-md-5">
                <label for="direccion">Direccion:</label>
                <input wire:model='direccion' type="text" class="form-control" placeholder="Direccion tienda" id="direccion">
                @error('direccion')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>

            {{-- Input Ciudad --}}
            <div class="form-group col-md-7">
                <label for="ciudad">Ciudad:</label>
                <input wire:model='ciudad' type="text" class="form-control" placeholder="Ciudad tienda" id="ciudad">
                @error('ciudad')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>

            {{-- Input imagen --}}
            <div class="form-group col-md-6">

                <label for="image">Imagen:</label>
                <input wire:model='image' type="file" id="image" accept="image/*">
                
                @error('image')
                    <div class="alert alert-danger w-100 mt-2">{{$message}}</div>
                @enderror
            </div>
            
            {{-- imagen --}}
            <div class="form-group col-md-6">

            
                <x-image :item="$shop" size="200" float="float-right" />
    

                @if ($this->image)
                <img src="{{$image->temporaryUrl()}}" class="rounded float-right" width="200">
                @endif

            </div>


        </div>
        
        <hr>
    <button wire:loading.attr='disabled' class="btn btn-primary float-right">
        Editar
    </button>    

    </form>
 </x-modal>





</div>


