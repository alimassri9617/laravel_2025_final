@extends('layouts.driver')

@section('title', 'Available Deliveries')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Available Deliveries</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Recent Deliveries Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Deliveries</h5>
        </div>
        <div class="card-body">
            @if($recentDeliveries->isEmpty())
                <div class="alert alert-info">No deliveries found</div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pickup</th>
                            <th>Destination</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDeliveries as $delivery)
                        <tr>
                            <td>#{{ $delivery->id }}</td>
                            <td>{{ $delivery->pickup_location }}</td>
                            <td>{{ $delivery->destination }}</td>
                            <td>${{ number_format($delivery->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-status bg-{{ 
                                    $delivery->status == 'completed' ? 'success' : 
                                    ($delivery->status == 'pending' ? 'warning' : 
                                    ($delivery->status == 'cancelled' ? 'danger' : 'primary'))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </td>
                            <td>
                                
                                @if($delivery->status == 'completed')
                                    <span class="badge bg-success">Yes</span>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2" disabled>Completed</button>
                                    <a href="{{ route('driver.chat.show', $delivery->id) }}" class="btn btn-success btn-sm mt-2 ms-2">
                                        <i class="bi bi-chat-dots me-1"></i> Chat with Client
                                    </a>
                                @elseif($delivery->status == 'pending')
                                    <form action="{{ route('driver.accept-delivery', $delivery->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mt-2">Accept</button>
                                    </form>
                                    <form action="{{ route('driver.reject-delivery', $delivery->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm mt-2">Reject</button>
                                    </form>
                                @elseif($delivery->status == 'accepted')
                                    <form action="{{ route('driver.complete', $delivery->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Mark as Complete</button>
                                    </form>
                                    <a href="{{ route('driver.chat.show', $delivery->id) }}" class="btn btn-success btn-sm mt-2 ms-2">
                                        <i class="bi bi-chat-dots me-1"></i> Chat with Client
                                    </a>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                                
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
@endsection
