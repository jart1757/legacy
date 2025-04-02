<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Legacy</title>
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
        /* Clase para iniciar en nueva página */
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LEGACY</h2>
    </div>
    

    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: none;">
        <tr>
            <td style="text-align: left; width: 50%; border: none;">
                <h4>Usuario: {{ $sales->first()->delivery->name ?? 'N/A' }}</h4>
            </td>
            <td style="text-align: center; width: 50%; border: none;">
                <h4>Departamento: {{ $sales->first()->departamento ?? 'N/A' }}</h4>
            </td>
            <td style="text-align: right; width: 50%; border: none;">
                <h4>Fecha: {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</h4>
            </td>
        </tr>
    </table>

    <!-- Tabla completa -->
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
                $i = 1;
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
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                @foreach ($productosTotales as $producto => $cantidad)
                    <th>{{ $producto }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Cantidad</strong></td>
                @foreach ($productosTotales as $cantidad)
                    <td>{{ $cantidad }}</td>
                @endforeach
            </tr>
            <tr class="table-primary">
                <td><strong>Total</strong></td>
                <td colspan="{{ count($productosTotales) }}" class="fw-bold">{{ array_sum($productosTotales) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Bloque de "Recibí conforme" o "Enviado a" (parte de la tabla completa) -->
    @if (trim(strtoupper($sales->first()->departamento ?? '')) === 'LA PAZ')
        <div style="text-align: center; margin-top: 70px;">
            <p>___________________________</p>
            <h3>RECIBÍ CONFORME</h3>
            <p><strong>Nombre:</strong> ___________________________________________</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</p>
        </div>
    @else
        <div style="text-align: center; margin-top: 70px;">
            <h3>ENVIADO A:</h3>
            <p>_____________________________________________________________________</p>
        </div>
        <div class="observaciones">
            <h3>OBSERVACIONES:</h3>
            <textarea></textarea>
        </div>
    @endif

    <!-- Si el buscador está vacío, se muestra la tabla resumida en una nueva hoja -->
    @if(trim($search ?? '') == '')
        <div class="page-break"></div>
        <center><h3>REPORTE LEGACY</h3></center>
        <table style="width: 100%; border-collapse: collapse; border: none;">
          <tr style="border: none;">
            <td style="text-align: right; border: none;">
              <h4>Fecha Inicio:  {{ \Carbon\Carbon::parse($fechaInicio)->format('d-m-Y') }}</h4>
            </td>

            <td style="text-align: left; border: none;">
              <h4>Fecha Final: {{ \Carbon\Carbon::parse($fechaFinal)->format('d-m-Y') }}</h4>
            </td>
          </tr>
        </table>
        
        <table>
            <thead>
                <tr>
                    <th>Carnet</th>
                    <th>Nombre</th>
                    <th>Fecha de Pago</th>
                    <th>Total</th>
                    <th>Descuento</th>
                    <th>Factura</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->client->telefono }}</td>
                        <td>{{ $sale->client->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->fechaing)->format('d-m-Y') }}</td>
                        <td>{{ number_format($sale->total, 2) }}</td>
                        <td>{{ number_format($sale->descuento, 2) }}</td>
                        <td><!-- Vacío para llenar manualmente la factura --></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
