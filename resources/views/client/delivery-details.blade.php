<!DOCTYPE html>
<html>
<head>
    <title>Delivery Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">DeliveryApp</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    Welcome, {{ $clientName }}
                </span>
                <a href="{{ route('client.logout') }}" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Delivery #{{ $delivery->id }}</h2>
            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Delivery Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6>Pickup Information</h6>
                            <p class="mb-1"><strong>Location:</strong> {{ $delivery->pickup_location }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Destination Information</h6>
                            <p class="mb-1"><strong>Location:</strong> {{ $delivery->destination }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6>Delivery Specifications</h6>
                            <p class="mb-1"><strong>Package Type:</strong> {{ ucfirst($delivery->package_type) }}</p>
                            <p class="mb-1"><strong>Delivery Type:</strong> {{ ucfirst($delivery->delivery_type) }}</p>
                            <p class="mb-1"><strong>Delivery Date:</strong> {{ $delivery->delivery_date }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Status & Payment</h6>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ 
                                    $delivery->status == 'completed' ? 'success' : 
                                    ($delivery->status == 'pending' ? 'warning' : 'primary')
                                }}">
                                    {{ ucfirst($delivery->status) }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Amount:</strong> ${{ number_format($delivery->amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                @if($delivery->special_instructions)
                <div class="mb-3">
                    <h6>Special Instructions</h6>
                    <p>{{ $delivery->special_instructions }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 