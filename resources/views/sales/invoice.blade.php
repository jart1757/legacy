<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura venta</title>

    <style>
        .b{
            border: 1px solid black;

        }

        .shop-info{
            display: block;
            padding: 3px;
            font-size: 12.5px;
        }

        .factura-id{
            font-size: 1.5rem;
            font-style: normal;
            color: #525659;
        }

        .factura-fecha{
            font-size: 1rem;
            font-style: normal;
            color: #525659;
        }

        .productos{
            text-align: center;
            margin-top: 1rem;
        }

        .productos thead{
            background-color: #525659ab;
            color: white;
        }

        .productos tr:nth-child(even){
            background-color: #ddd;
        }

        th,td{
            padding: 10px;
        }

        .badge{
            background-color: #5256598d;
            color: white;
            padding: 3px;
            border-radius:100%;
            font-weight: 500;
            width: 10px;
            margin: 0 auto;


                
        }

    </style>
</head>
<body>

    <table class="" width="100%">
        <tr>
            <td width="25%">
                <img src="{{public_path().'/'.'storage/'.$shop->image->url}}" width="150px">

            </td>
            <td width="50%" style="text-align: center">
                <h1>{{$shop->name}}</h1>

                @if($shop->slogan)
                    <p>{{$shop->slogan}}</p>
                @endif

            </td>
            <td width="25%">

                @if($shop->telefono)
                    <span class="shop-info">
                       <b>Telefono:</b> {{$shop->telefono}}
                    </span>
                @endif    
                @if($shop->email)
                    <span class="shop-info">
                        <b>Email:</b> {{$shop->email}}
                    </span>
                @endif                   
                @if($shop->direccion)
                    <span class="shop-info">
                        <b>Direccion:</b> {{$shop->direccion}}
                    </span>
                @endif  
                @if($shop->ciudad)
                <span class="shop-info">
                    <b>Ciudad:</b>{{$shop->ciudad}}
                </span>
                @endif                 
            </td>
        </tr>

    </table>

    <table width="100%">
        <tr>
            <td width=33%>
                <h2 style="margin-bottom: .5rem">Cliente:</h2>

                @if($sale->client->name)
                <span class="shop-info">
                    <b>Nombre: </b>{{$sale->client->name}}
                </span>
                @endif  

                @if($sale->client->identificacion)
                <span class="shop-info">
                    <b>Identificacion: </b>{{$sale->client->identificacion}}
                </span>
                @endif  

                @if($sale->client->telefono)
                <span class="shop-info">
                    <b>Telefono: </b>{{$sale->client->telefono}}
                </span>
                @endif  

                @if($sale->client->email)
                <span class="shop-info">
                    <b>Email: </b>{{$sale->client->email}}
                </span>
                @endif 

                @if($sale->client->empresa)
                <span class="shop-info">
                    <b>Empresa: </b>{{$sale->client->empresa}}
                </span>
                @endif 

                @if($sale->client->nit)
                <span class="shop-info">
                    <b>Nit: </b>{{$sale->client->nit}}
                </span>
                @endif 
            </td>
            <td width="33%">
                <h2 style="text-align: center">
                    Factura: <span class="factura-id">FV-{{$sale->id}}</span>
                </h2>
            </td>
            <td width="33%">
                <h3>
                    Fecha: <span class="factura-fecha">{{$sale->created_at}}</span>
                </h3>
            </td>
        </tr>
    </table>

    <table width="100%" class="productos">
        <thead>
            <th>#</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th style="text-align: center">Items</th>
            <th>Subtotal</th>
        </thead>

        <tbody>
            @forelse ($sale->items as $item)
            <tr>
                <td>{{++$loop->index}}</td>
                <td>{{$item->name}}</td>
                <td>{{money($item->price)}}</td>
                <td>
                    <div class="badge">
                        {{$item->qty}}
                    </div>
                </td>
                <td>
                    {{money($item->price*$item->qty)}}
                </td>
            </tr>
                
            @empty
            <tr>
                <td colspan="5">Sin registros</td>

            </tr> 
            @endforelse
            <tr>
                <td colspan="3"></td>
                <td>Total:</td>
                <td>
                    <b>{{money($sale->total)}}</b>
                </td>
            </tr>
        </tbody>

    </table>

    <table width="100%" style="text-align: center; margin-top:5rem;">
        <tr>
            <td>
                ___________________________ <br> 
                <b>{{$sale->user->name}}</b> <br>
                Vendedor
            </td>
        </tr>

    </table>

    <p style="text-align: center">Muchas gracias por tu compra!</p>
    
</body>
</html>