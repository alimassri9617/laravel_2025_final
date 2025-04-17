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

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Deliveries in your work area</h5>
                </div>
                <div class="card-body">
                    @if($deliveries->isEmpty())
                        <div class="alert alert-info">No available deliveries in your area</div>
                    @else
                        <div class="table-responsive">
                           <!-- Add client information to the table -->
<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Pickup</th>
            <th>Destination</th>
            <th>Package</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($deliveries as $delivery)
        <tr>
            <td>#{{ $delivery->id }}</td>
            <td>
                {{ $delivery->client->fname }} {{ $delivery->client->lname }}<br>
                <small class="text-muted">{{ $delivery->client->phone }}</small>
            </td>
            <td>{{ $delivery->pickup_location }}</td>
            <td>{{ $delivery->destination }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $delivery->package_type)) }}</td>
            <td>${{ number_format($delivery->amount, 2) }}</td>
            <td>
                <form method="POST" action="{{ route('driver.accept-delivery', $delivery->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Accept
                    </button>
                </form>
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