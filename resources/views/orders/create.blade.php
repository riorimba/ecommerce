@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Order</h1>
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required>
            @error('total_amount')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}" required>
            @error('status')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="order_items" class="form-label">Order Items</label>
            <div id="order_items">
                <div class="order-item mb-2">
                    <select class="form-control @error('order_items.0.product_id') is-invalid @enderror" name="order_items[0][product_id]" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" class="form-control mt-2 @error('order_items.0.quantity') is-invalid @enderror" name="order_items[0][quantity]" placeholder="Quantity" required>
                    <input type="number" class="form-control mt-2 @error('order_items.0.price') is-invalid @enderror" name="order_items[0][price]" placeholder="Price" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" id="add-order-item">Add Item</button>
            @error('order_items')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>

<script>
    document.getElementById('add-order-item').addEventListener('click', function() {
        var orderItems = document.getElementById('order_items');
        var index = orderItems.children.length;
        var newItem = document.createElement('div');
        newItem.classList.add('order-item', 'mb-2');
        newItem.innerHTML = `
            <select class="form-control" name="order_items[${index}][product_id]" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" class="form-control mt-2" name="order_items[${index}][quantity]" placeholder="Quantity" required>
            <input type="number" class="form-control mt-2" name="order_items[${index}][price]" placeholder="Price" required>
        `;
        orderItems.appendChild(newItem);
    });
</script>
@endsection