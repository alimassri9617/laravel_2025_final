@extends('layouts.driver')

@section('title', 'Driver Dashboard')

@section('content')
    <h2 class="mb-4">Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Assigned Deliveries</h5>
                    <p class="display-4">{{ $deliveries->whereIn('status', ['accepted', 'in_progress'])->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Completed Deliveries</h5>
                    <p class="display-4">{{ $deliveries->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="display-4">${{ number_format($deliveries->where('status', 'completed')->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="mt-4">
        <h4>Notifications</h4>
        @if($notifications->isEmpty())
            <p>No notifications</p>
        @else
            <ul class="list-group">
                @foreach($notifications as $notification)
                    <li class="list-group-item {{ $notification->read ? 'list-group-item-secondary' : '' }}">
                        <strong>{{ $notification->title }}</strong><br>
                        {{ $notification->body }}<br>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
