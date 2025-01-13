<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h1>Invoice</h1>
    <p><strong>User:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>
    <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
    <p><strong>Payment Type:</strong> {{ $order->payment_type }}</p>
    <p><strong>Paid At:</strong> {{ $order->pay_at ? $order->pay_at->format('d-m-Y H:i:s') : 'Not Paid Yet' }}</p>

    <h2>Items</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity * $item->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: ${{ $order->total }}</h3>
</body>
</html>