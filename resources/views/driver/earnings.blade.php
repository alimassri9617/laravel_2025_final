@extends('layouts.driver')

@section('title', 'Earnings Report')

@section('content')
    <div class="container-fluid p-4">
        <h2 class="mb-4">Earnings Report</h2>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Earnings</h5>
                        <p class="display-4">${{ number_format($totalEarnings, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pending Earnings</h5>
                        <p class="display-4">${{ number_format($pendingEarnings, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Completed Deliveries</h5>
            </div>
            <div class="card-body">
                @if($completedDeliveries->isEmpty())
                    <div class="alert alert-info">No completed deliveries yet</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Pickup</th>
                                    <th>Destination</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedDeliveries as $delivery)
                                <tr>
                                    <td>#{{ $delivery->id }}</td>
                                    <td>{{ $delivery->updated_at->format('Y-m-d') }}</td>
                                    <td>{{ $delivery->pickup_location }}</td>
                                    <td>{{ $delivery->destination }}</td>
                                    <td>${{ number_format($delivery->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
