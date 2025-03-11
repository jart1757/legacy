<tbody>
    @foreach ($productos as $product)
    <tr>
        <td>{{$product->id}}</td>
        <td>{{$product->name}}</td>
        <td>{!!$product->precio!!}</td>
        <td>{!!$product->category->name!!}</td>
        <td>
            @if($product->stock <= $product->stock_minimo)
                <span class="badge badge-pill badge-danger">{{$product->stock}}</span>
            @else
                <span class="badge badge-pill badge-success">{{$product->stock}}</span>
            @endif
        </td>
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
    @endforeach
</tbody>
