<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tshirt"></i> Productos</h3>
    </div>

    <div class="card-body">

        <x-table>

            <x-slot:thead>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio.vt</th>
                <th scope="col">Stock</th>
                <th scope="col">...</th>

            </x-slot>
            @forelse ($products as $product)
            
                <livewire:sale.product-row :product="$product" :wire:key="$product->id" >

            @empty
                <tr>
                    <td colspan="10">Sin Registros</td>
                </tr>                
            @endforelse

   
    </x-table>

</div>
<div class="card-footer">
    {{$products->links()}}
</div>

</div>
