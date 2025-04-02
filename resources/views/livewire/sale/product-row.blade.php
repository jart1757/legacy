@php
    $typeColors = [
        'BONIFICADO' => 'bg-success text-white',
        'RECONSUMO' => 'bg-primary text-white',
        'PREFERENTE' => 'bg-warning text-dark',
        'MAYORISTA' => 'bg-danger text-white',
        'OFICINA' => 'bg-info text-white',
    ];
@endphp<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tshirt"></i> Productos</h3>
    </div>

    <div class="card-body">
        @if ($products->count())
            <x-table>
                <x-slot:thead>
                    <tr>
                        <th>Producto</th>
                        @foreach ($products as $product)
                            <th class="text-center {{ $typeColors[$product->category->name] ?? 'bg-secondary text-white' }}">
                                {!! $product->name !!}
                            </th>
                        @endforeach
                    </tr>
                </x-slot>

                <tr>
                    <td>Acción</td>
                    @foreach ($products as $product)
                        <td class="text-center">
                            <button
                                wire:click="addProduct({{ $product->id }})"
                                class="btn btn-primary btn-sm"
                                wire:loading.attr="disabled"
                                wire:target="addProduct"
                                title="Agregar">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Cantidad</td>
                    @foreach ($products as $product)
                        <td class="text-center">
                            {!! $stockLabel !!}
                        </td>
                    @endforeach
                </tr>

              
            </x-table>
        @else
            <div class="text-center">Sin Registros</div>
        @endif
    </div>

    <div class="card-footer d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
