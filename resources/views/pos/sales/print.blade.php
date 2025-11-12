<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->invoice_no }}</title>
    <style>
        body { font-family: monospace; width: 300px; }
        .header { text-align: center; margin-bottom: 10px; }
        .products { width: 100%; border-collapse: collapse; }
        .products th, .products td { border-bottom: 1px dashed #000; padding: 4px 0; }
        .total { text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>C4 Fitness Gym</h3>
        <p>Invoice: {{ $sale->invoice_no }}</p>
        <p>Date: {{ $sale->created_at->format('Y-m-d H:i') }}</p>
        <p>Payment: {{ $sale->payment_method }}</p>
    </div>

    <table class="products">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-right">Rs. {{ number_format($item->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Subtotal: Rs. {{ number_format($sale->subtotal,2) }}</p>
        <p>Tax: Rs. {{ number_format($sale->tax,2) }}</p>
        <p><strong>Total: Rs. {{ number_format($sale->total,2) }}</strong></p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
