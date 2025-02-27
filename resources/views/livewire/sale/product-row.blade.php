<tr>
    <td>{{$product->id}}</td>

    <td>{{$product->name}}</td>
    <td>{!!$product->precio!!}</td>
    <td>{!!$stockLabel!!}</td>
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