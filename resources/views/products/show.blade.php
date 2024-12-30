@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Details</h1>
    <div class="card">
        <div class="card-header">
            {{ $product->name }}
        </div>
        <div class="card-body">
        <h5 class="card-title">Category: {{ $product->category->name ?? 'No Category' }}</h5>
            <p class="card-text">Description: {{ $product->description }}</p>
            <p class="card-text">Price: ${{ $product->price }}</p>
            <p class="card-text">Stock: {{ $product->stock }}</p>
            <div class="mb-3">
                <label class="form-label">Product Images</label>
                <div>
                    @if($product->images->isNotEmpty())
                        @foreach($product->images as $image)
                            <img src="{{ asset($image->image_path) }}" alt="Product Image" width="100" style="margin-right: 10px;">
                        @endforeach
                    @else
                        <p>No images available for this product.</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Back to Products</a>
        </div>
    </div>
</div>
@endsection