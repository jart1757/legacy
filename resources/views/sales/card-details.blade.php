<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cart-plus"></i> Detalles venta  
        </h3>
        <div class="card-tools">
            <!-- Conteo de productos -->
            <i class="fas fa-tshirt" title="Numero productos"></i>
            <span class="badge badge-pill bg-purple">{{$cart->count()}} </span>
            <!-- Conteo de articulos -->
            <i class="fas fa-shopping-basket ml-2" title="Numero items"></i>
            <span class="badge badge-pill bg-purple">{{$totalArticulos}} </span>

            {{-- Boton crear venta --}}

            <button wire:click="{{isset($sale) ? 'editSale' : 'createSale'}}
            " class="btn bg-purple ml-2">
                <i class="fas fa-cart-plus"></i>
                {{isset($sale) ? 'Editar venta' : 'Generar venta'}}
            </button>
            
        </div>
    </div>
<!-- card-body -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><i class="fas fa-image"></i></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio.vt</th>
                        <th scope="col" width="15%">Qty</th>
                        <th scope="col">Sub total</th>
                        <th scope="col">...</th>
                    </tr>

                </thead>
                <tbody>
                   @forelse ($cart as $product)
                       
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>
                            <x-image :item="$product->associatedModel" size="60" />

                        </td>
                        <td>{{$product->name}}</td>
                        <td>{!!$product->associatedModel->precio!!}</td>
                        <td>
                            <!-- Botones para aumentar o disminuir la cantidad del producto en el carrito -->
                            <button
                             wire:click='decrement({{$product->id}})'
                             class="btn btn-primary btn-xs"
                             wire:loading.attr='disabled'
                             wire:target='decrement'
                             >
                                - 
                            </button>

                            <span class="mx-1">{{$product->quantity}}</span>

                            <button
                             wire:click='increment({{$product->id}})'
                             class="btn btn-primary btn-xs"
                             wire:loading.attr='disabled'
                             wire:target='increment'
                             {{$product->quantity >= $product->associatedModel->stock ? 'disabled' : ''}}
                             >
                                +
                            </button>
                            
                        </td>
                        <td>{{money($product->quantity*$product->price)}}</td>
                        <td>
                            <!-- Boton para eliminar el producto del carrito -->
                            <button 
                                class="btn btn-danger btn-xs"
                                title="Eliminar"
                                wire:click='removeItem({{$product->id}},{{$product->quantity}})'>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">Sin Registros</td>
                    </tr>                      
                    @endforelse

                    <tr>
                        <td colspan="4"></td>
                        <td>
                            <h5>Total:</h5>
                        </td>
                        <td>
                            <h5>
                                <span class="badge badge-pill badge-secondary">
                                    {{money($total)}}
                                </span>
                            </h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>

                        <td colspan="7">
                            <strong>Total en letras:</strong>
                            {{numeroLetras($total)}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
<!-- end-card-body -->
</div>
<!-- end-card -->