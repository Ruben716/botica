<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta #{{ $sale->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Boleta de Venta #{{ $sale->id }}</h2>

    <p><strong>Cliente:</strong> {{ $sale->client->name }}</p>
    <p><strong>Email:</strong> {{ $sale->client->email }}</p>
    <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>S/ {{ number_format($item->product->price, 2) }}</td>
                    <td>S/ {{ number_format($item->quantity * $item->product->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">TOTAL: S/ {{ number_format($sale->total, 2) }}</p>

    <p style="text-align: center; margin-top: 20px;">¡Gracias por su compra!</p>
</body>
</html>
