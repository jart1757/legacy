@php
    $typeColors = [
        'BONIFICADO' => 'bg-success text-white',
        'RECONSUMO' => 'bg-primary text-white',
        'PREFERENTE' => 'bg-warning text-dark',
        'MAYORISTA' => 'bg-danger text-white',
        'OFICINA' => 'bg-info text-white',
    ];
@endphp

<tr>
    <td>{{$product->id}}</td>
    <td>{{$product->name}}</td>
    <td class="p-2 rounded {{ $typeColors[$product->category->name] ?? 'bg-secondary text-white' }}">
        {!! $product->category->name !!}
    </td>
    <td>{!! $stockLabel !!}</td>
    <td>
        <button
            wire:click="addProduct({{$product->id}})"
            class="btn btn-primary btn-sm"
            wire:loading.attr='disabled'
            wire:target='addProduct'
            title="Agregar">
            <i class="fas fa-plus-circle"></i>
        </button>
    </td>
</tr>
