@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Order Details</h1>
    <div class="card mt-4">
        <div class="card-header">
            Order #{{ $order->id }}
        </div>
        <div class="card-body">
            <p><strong>User:</strong> {{ $order->user->name }}</p>
            <p><strong>Total:</strong> {{ $order->total }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Created At:</strong> {{ $order->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $order->updated_at }}</p>
        </div>
    </div>

    <h2 class="mt-4">Order Items</h2>
    <table class="table mt-2">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->product_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection