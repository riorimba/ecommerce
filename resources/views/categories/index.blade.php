@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1>Categories</h1>
        <div>
            <a href="{{ route('categories.export') }}" class="btn btn-success">Export</a>
            <button class="btn btn-info" data-toggle="modal" data-target="#importModal">Import</button>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Create Category</a>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    <table id="categories-table" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
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
                <h5 class="modal-title" id="importModalLabel">Import Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Choose Excel File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <a href="{{ route('categories.template') }}" class="btn btn-link">Download Template</a>
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
            $('.alert-danger').fadeOut('slow');
        }, 3000);

        var table = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('categories.getCategories') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            responsive: true,
        });

        $('#categories-table').on('click', '.delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload();
                },
                error: function(response) {
                    alert('Error deleting category');
                }
            });
        });
    });
</script>
@endsection