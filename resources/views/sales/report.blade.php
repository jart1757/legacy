<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f8f8f8; font-size: 13px; }
        .header { text-align: center; margin-bottom: 10px; }
        .observaciones textarea { width: 100%; height: 80px; border: 1px solid #000; padding: 5px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ request('delivery_name') ? 'LEGACY' : 'REPORTE LEGACY' }}</h2>
    </div>

    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: none;">
        <tr>
            @if(request('delivery_name'))
                <td style="text-align: left; width: 50%; border: none;"><h4>Usuario: {{ $sales->first()->delivery->name ?? 'N/A' }}</h4></td>
                <td style="text-align: left; width: 50%; border: none;"><h4>Departamento: {{ $sales->first()->departamento ?? 'N/A' }}</h4></td>
            @else
                <td style="text-align: left; width: 50%; border: none;"><h4>Fecha Inicio: {{ \Carbon\Carbon::parse($fechaInicio)->format('d-m-Y') }}</h4></td>
                <td style="text-align: right; width: 50%; border: none;"><h4>Fecha Final: {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</h4></td>
            @endif
        </tr>
    </table>

    @if(request('delivery_name')) 
        {{-- Tabla filtrada por delivery --}}
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Codigo</th>
                    <th>Cliente</th>
                    <th>Carnet</th>
                    <th>Total</th>
                    <th>Detalle de Productos</th>
                    <th>Fecha de Pago</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $sale->client->identificacion }}</td>
                        <td>{{ $sale->client->name }}</td>
                        <td>{{ $sale->client->telefono }}</td>
                        <td>{{ number_format($sale->total, 2) }}</td>
                        <td>
                            @foreach ($sale->items as $item)
                                <span>{{ $item->name }} ({{ $item->qty }})</span>
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($sale->fechaing)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else 
        {{-- Tabla cuando no se filtra por nombre en delivery --}}
        <table>
            <thead>
                <tr>
                    <th>Teléfono</th>
                    <th>Nombre</th>
                    <th>Fechaing</th>
                    <th>Total</th>
                    <th>Descuento</th>
                    <th>Factura</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->client->telefono }}</td>
                        <td>{{ $sale->client->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->fechaing)->format('d-m-Y') }}</td>
                        <td>{{ number_format($sale->total, 2) }}</td>
                        <td>{{ number_format($sale->descuento ?? 0, 2) }}</td>
                        <td></td> {{-- Columna vacía para llenar manualmente --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
