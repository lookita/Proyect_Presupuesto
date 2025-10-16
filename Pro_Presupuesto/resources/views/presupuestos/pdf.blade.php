<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Presupuestos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Listado de Presupuestos</h1>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuestos as $presupuesto)
            <tr>
                <td>{{ $presupuesto->cliente->nombre }}</td>
                <td>{{ $presupuesto->created_at->format('d/m/Y') }}</td>
                <td>${{ number_format($presupuesto->total, 2) }}</td>
                <td>{{ ucfirst($presupuesto->estado) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>