<x-card cardTitle="Detalles cliente">
    <x-slot:cardTools>
       <a href="{{route('clients')}}" class="btn btn-primary">
        <i class="fas fa-arrow-circle-left"></i> Regresar
       </a>
    </x-slot>
    
    <div class="row">
        <div class="col-md-4">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile">

                    <h2 class="profile-username text-center mb-2">{{$client->name}}</h2>
                
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <b>Identificacion</b> <a class="float-right">{{$client->identificacion}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{$client->email}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Telefono</b> <a class="float-right">{{$client->telefono}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Empresa</b> <a class="float-right">{{$client->empresa}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Nit</b> <a class="float-right">{{$client->nit}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Creado</b> <a class="float-right">{{$client->created_at}}</a>
                        </li>
                    </ul>
                    
                </div>
            
            </div>
            
        </div>
        <div class="col-md-8">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Productos</th>
                        <th>Articulos</th>
                        <th>...</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                    <tr>
                        <td>
                           <b>FV-{{$sale->id}}</b>
                        </td>
                        <td>
                            {{money($sale->total)}}
                        </td>
                        <td>
                            <span class="badge badge-pill badge-primary">
                                {{$sale->items->count()}}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-pill badge-primary">
                                {{$sale->items->sum('pivot.qty')}}
                            </span>                            
                        </td>
                        <td>
                            <a href="{{route('sales.show',$sale)}}" class="btn btn-primary btn-sm">
                                Ver venta
                            </a>
                        </td>
                    </tr>                        
                    @endforeach

                    
                </tbody>
            </table>
            {{$sales->links()}}
        </div>    
    </div>

 </x-card>
