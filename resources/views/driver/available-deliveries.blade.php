<!DOCTYPE html>
<html>
<head>
    <title>Available Deliveries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="d-flex">
    <!-- Sidebar (same as dashboard) -->
   

    <!-- Main Content -->
    <div class="main-content bg-light">
        <!-- Top Navigation (same as dashboard) -->
       

        <!-- Available Deliveries Content -->
        <div class="container-fluid p-4">
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
                                            <a href="{{ route('driver.chat.show', $delivery->id) }}" class="btn btn-info btn-sm mt-2 ms-2">Chat with Client</a>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
