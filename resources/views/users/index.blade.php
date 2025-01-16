@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1>Users</h1>
        <div>
            <a href="{{ route('users.export') }}" class="btn btn-success">Export</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
        </div>
    </div>
    @if($errors->has('password'))
        <div class="alert alert-danger">
            {{ $errors->first('password') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    <table id="users-table" class="table mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->role_name }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-success btn-sm">Show</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" id="delete-form-{{ $user->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }}, {{ $user->role_id }})">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="confirm-delete-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Password</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="text-danger" id="password-error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#users-table').DataTable();

        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
            $('.alert-danger').fadeOut('slow');
        }, 3000);
    });
</script>
<script>
    function confirmDelete(userId, roleId) {
        if (roleId === 1) { // Admin role
            $('#confirm-delete-form').attr('action', '/users/' + userId);
            $('#confirmDeleteModal').modal('show');
        } else {
            // Submit the form directly for non-admin users
            $('#delete-form-' + userId).submit();
        }
    }
    @if($errors->has('password'))
        $(document).ready(function() {
            $('#confirmDeleteModal').modal('show');
            $('#password-error').text('{{ $errors->first('password') }}');
        });
    @endif
</script>

@endsection