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
    <table id="products-table" class="table mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Images</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.getProducts') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'category.name', name: 'category.name', defaultContent: 'No Category' },
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'stock', name: 'stock' },
                { data: 'images', name: 'images', orderable: false, searchable: false, render: function(data, type, row) {
                    if (data.length > 0) {
                        return '<img src="' + data[0].image_path + '" alt="Product Image" style="max-width: 100px;">';
                    } else {
                        return 'No Image';
                    }
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            responsive: true,
        });
    });
</script>
@endsection