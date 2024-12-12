@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">Edit Product</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                            @if($product->images->isNotEmpty())
                            <div class="mt-2">
                                @foreach($product->images as $image)
                                <img src="{{ asset($image->image_path) }}" alt="Product Image" width="100" style="margin-right: 10px;">
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection