@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notifications</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->count() > 0)
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{ $notification->data['message'] }}
                            <br>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            @if(!$notification->read_at)
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-primary">Mark as Read</a>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-eraser"></i></button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $notifications->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-info">
            No notifications found.
        </div>
    @endif
</div>
@endsection