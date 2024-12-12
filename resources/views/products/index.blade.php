@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1>Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <!-- <th>Description</th> -->
                <th>Price</th>
                <th>Stock</th>
                <th>Images</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->name }}</td>
                    <!-- <td>{{ $product->description }}</td> -->
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset($product->images->first()->image_path) }}" alt="Product Image" style="max-width: 100px;">
                    @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-success btn-sm">Show</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection