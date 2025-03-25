<div>
    <x-card cardTitle="Listado ventas ({{$this->totalRegistros}})">
       <x-slot:cardTools>
            <div class="d-flex align-items-center">
                <button wire:click="exportPDF" class="btn btn-danger">
                    <i class="far fa-file-pdf"></i> Exportar PDF
                </button>

                <span class="badge badge-info" style="font-size: 1.4rem">
                    Total: {{money($this->totalVentas)}}
                </span>

                <div class="mx-3">
                    <button class="btn btn-default" id="daterange-btn" wire:ignore>
                        <i class="far fa-calendar-alt"></i> 
                        <span>D-M-A - D-M-A</span>
                        <i class="fas fa-caret-down"></i>
                    </button>
                </div>

                <a href="{{route('sales.create')}}" class="btn btn-primary">
                    <i class="fas fa-cart-plus"></i> Crear Salida
                </a>
            </div>
       </x-slot:cardTools>

       <x-table>
          <x-slot:thead>
             <th>Codigo</th>
             <th>Cliente</th>
             <th>Cantidad de Productos</th>
             <th>Detalle de Productos</th>
             <th>Total</th>
             <th>Fecha de Pago</th>
             <th>Usuario</th>
             <th>Tipo</th>
             <th>Departamento de Destino</th>
             <th>Pedido</th>
             <th>Boleta</th>
             <th>PDF</th>
             <th>Ver</th>
             <th>Editar</th>
             <th>Eliminar</th>
          </x-slot>

          @php
              $totalCantidadProductos = 0;
              $productosPorVenta = [];
          @endphp

          @forelse ($sales as $sale)
             <tr>
                <td><span class="badge badge-primary">{{$sale->client->identificacion}}</span></td>
                <td>{{$sale->client->name}}</td>
                <td><span class="badge badge-pill bg-purple">
                    @foreach ($sale->items as $item)
                        @php
                            $totalCantidadProductos += $item->qty;
                            if (!isset($productosPorVenta[$item->name])) {
                                $productosPorVenta[$item->name] = 0;
                            }
                            $productosPorVenta[$item->name] += $item->qty;
                        @endphp
                    @endforeach
                    {{$sale->items->sum('qty')}}
                </span></td>

                <td>
                    <ul class="list-unstyled text-left">
                        @foreach ($sale->items as $item)
                            <li>
                                <span class="badge badge-info">{{$item->name}}</span> 
                                <span class="badge badge-primary">{{$item->qty}}</span>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td><span class="badge badge-secondary">{{money($sale->total)}}</span></td>
                <td>{{$sale->fechaing}}</td>
                <td>{{$sale->delivery->name ?? 'Sin delivery'}}</td>
                <td>{{$sale->client->category->name ?? 'Sin categoría'}}</td>
                <td>{{$sale->departamento}}</td>

                <td>
                    @if($sale->pedido_path)
                        @php
                            $extension = pathinfo($sale->pedido_path, PATHINFO_EXTENSION);
                        @endphp
                
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                            <!-- Si es una imagen -->
                            <a href="{{ asset('storage/' . $sale->pedido_path) }}" target="_blank">
                                <i class="fas fa-image"></i>
                            </a>
                        @elseif($extension == 'pdf')
                            <!-- Si es un PDF -->
                            <a href="{{ asset('storage/' . $sale->pedido_path) }}" target="_blank">
                                Ver PDF del pedido
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                
                <td>
                    @if($sale->boleta_path)
                        @php
                            $extension = pathinfo($sale->boleta_path, PATHINFO_EXTENSION);
                        @endphp
                
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                            <!-- Si es una imagen -->
                            <a href="{{ asset('storage/' . $sale->boleta_path) }}" target="_blank">
                                <i class="fas fa-image"></i>
                            </a>
                        @elseif($extension == 'pdf')
                            <!-- Si es un PDF -->
                            <a href="{{ asset('storage/' . $sale->boleta_path) }}" target="_blank">
                                Ver PDF de la boleta
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                

                <td>
                    <a href="{{route('sales.invoice',$sale)}}" class="btn bg-navy btn-sm" target="_blank">
                        <i class="far fa-file-pdf"></i>
                    </a>
                </td>

                <td>
                    <a href="{{route('sales.show',$sale)}}" class="btn btn-success btn-sm">
                        <i class="far fa-eye"></i>
                    </a>
                </td>

                <td>
                    <a href="{{route('sales.edit',$sale)}}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                    </a>
                </td>

                <td>
                    <button wire:click.prevent="$dispatch('delete', {id: {{$sale->id}}, eventName:'destroySale'})" class="btn btn-danger btn-sm">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </td>
             </tr>
          @empty
             <tr class="text-center">
                <td colspan="18">Sin registros</td>
             </tr>
          @endforelse
       </x-table>

       <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                @foreach ($productosPorVenta as $producto => $cantidad)
                                    <th>{{ $producto }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Cantidad</strong></td>
                                @foreach ($productosPorVenta as $cantidad)
                                    <td>{{ $cantidad }}</td>
                                @endforeach
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total</strong></td>
                                <td colspan="{{ count($productosPorVenta) }}" class="fw-bold">{{ $totalCantidadProductos }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <x-slot:cardFooter>
        <div class="d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    </x-slot>
    
    </x-card>
    @section('styles')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

@endsection

@section('js')

    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>

    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

    <script>
   
        $('#daterange-btn').daterangepicker(
            {
                ranges   : {
                'Default'       : [moment().startOf('year'), moment()],
                'Hoy'       : [moment(), moment()],
                'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 Dias' : [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes'  : [moment().startOf('month'), moment().endOf('month')],
                'Ultimos Mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('year'),
                endDate  : moment()
            },
            function (start, end) {

                dateStart = start.format('YYYY-MM-DD');
                dateEnd = end.format('YYYY-MM-DD');

                $('#daterange-btn span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));

                Livewire.dispatch('setDates',{fechaInicio: dateStart, fechaFinal: dateEnd});
        }

    );            
    
    </script>

@endsection
</div>
