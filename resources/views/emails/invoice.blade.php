<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
</head>

<body>
    <h1>Invoice for Your Order</h1>
    <p>Dear {{ $order->user->name }},</p>
    <p>Thank you for your order. Please find your invoice attached.</p>
    <p>Order ID: {{ $order->order_id }}</p>
    <p>Total: ${{ number_format($order->total, 2) }}</p>
    <p>Date: {{ $order->created_at->format('d-m-Y') }}</p>
</body>

</html>