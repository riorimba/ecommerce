@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Order Status</h1>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="id">Order ID</label>
            <input type="text" id="id" class="form-control" value="{{ $order->id }}" readonly>
        </div>

        <div class="form-group">
            <label for="user">User</label>
            <input type="text" id="user" class="form-control" value="{{ $order->user->name }}" readonly>
        </div>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="text" id="total" class="form-control" value="{{ $order->total }}" readonly>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" value="{{ $order->status }}">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>
@endsection