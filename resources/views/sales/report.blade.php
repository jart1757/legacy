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
        .detalle-productos span { display: inline-block; padding: 4px 8px; margin: 2px; border-radius: 4px; font-weight: bold; }
        .producto { background: #ffcccb; color: #b30000; } /* Rojo suave */
        .cantidad { background: #cce5ff; color: #004085; } /* Azul suave */
        .header { text-align: center; margin-bottom: 10px; }
        .sub-header { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; }
        .observaciones { margin-top: 20px; font-size: 14px; }
        .observaciones textarea { width: 100%; height: 80px; border: 1px solid #000; padding: 5px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LEGACY</h2>
    </div>

    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: none;">
        <tr>
            <td style="text-align: left; width: 50%; border: none;"><h4>Usuario: {{ $sales->first()->delivery->name ?? 'N/A' }}</h4></td>
            <td style="text-align: left; width: 50%; border: none;"><h4>Departamento: {{ $sales->first()->departamento ?? 'N/A' }}</h4></td>
            <td style="text-align: right; width: 50%; border: none;"><h4>Fecha: {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</h4></td>
        </tr>
    </table>

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
            @php
                $totalVentas = 0;
                $productosTotales = [];
                $i=1;
            @endphp

            @foreach ($sales as $sale)
                @php
                    $totalVentas += $sale->total;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $sale->client->identificacion }}</td>
                    <td>{{ $sale->client->name }}</td>
                    <td>{{ $sale->client->telefono }}</td>
                    <td>{{ number_format($sale->total, 2) }}</td>
                    <td>
                        <div class="detalle-productos">
                            @foreach ($sale->items as $item)
                                <span class="producto">{{ $item->name }}</span> 
                                <span class="cantidad">{{ $item->qty }}</span>

                                @php
                                    if (isset($productosTotales[$item->name])) {
                                        $productosTotales[$item->name] += $item->qty;
                                    } else {
                                        $productosTotales[$item->name] = $item->qty;
                                    }
                                @endphp
                            @endforeach
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($sale->fechaing)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>CANTIDAD DE PRODUCTOS:</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                @foreach ($productosTotales as $producto => $cantidad)
                    <th>{{ $producto }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Cantidad</th>
                @php $sumaTotal = array_sum($productosTotales); @endphp
                @foreach ($productosTotales as $cantidad)
                    <td>{{ $cantidad }}</td>
                @endforeach
                <td><strong>{{ $sumaTotal }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if (trim(strtoupper($sales->first()->departamento ?? '')) === 'LA PAZ')
    <div style="text-align: center; margin-top: 70px;">
    
        <p>___________________________</p>
        <h3>RECIBÍ CONFORME</h3>
        <p><strong>Nombre:</strong> ___________________________________________</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</p>
    </div>
@else
    <h3>ENVIADO A:</h3>
    <p>_____________________________________________________________________</p>

    <div class="observaciones">
        <h3>OBSERVACIONES:</h3>
        <textarea></textarea>
    </div>
@endif



</body>
</html>