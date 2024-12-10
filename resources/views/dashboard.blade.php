@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @else
                    @if (Auth::user()->role_id == 1)
                        <div class="alert alert-success">
                            You are logged in as Admin!
                        </div>
                    @elseif (Auth::user()->role_id == 2)
                        <div class="alert alert-success">
                            You are logged in as User!
                        </div>
                    @else
                        <div class="alert alert-success">
                            You are logged in!
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection