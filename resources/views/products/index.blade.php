@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1>Products</h1>
        <div>
            <a href="{{ route('products.export') }}" class="btn btn-success">Export</a>
            <button class="btn btn-info" data-toggle="modal" data-target="#importModal">Import</button>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
        </div>
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

<!-- Modal for Import -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Choose Excel File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <a href="{{ route('products.template') }}" class="btn btn-link">Download Template</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 3000);

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