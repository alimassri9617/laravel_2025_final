<!DOCTYPE html>
<html>
<head>
    <title>New Delivery</title>
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
            <h2>New Delivery Request</h2>
            <a href="{{ route('client.deliveries') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Deliveries
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Delivery Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('client.deliveries.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pickup_location" class="form-label">Pickup Location</label>
                            <input type="text" class="form-control" id="pickup_location" name="pickup_location" required>
                        </div>
                        <div class="col-md-6">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" class="form-control" id="destination" name="destination" required>
                        </div>
                        <div class="col-md-6">
                            <label for="package_type" class="form-label">Package Type</label>
                            <select class="form-select" id="package_type" name="package_type" required>
                                <option value="small">Small (0-5kg)</option>
                                <option value="medium">Medium (5-15kg)</option>
                                <option value="large">Large (15-30kg)</option>
                                <option value="extra_large">Extra Large (30+ kg)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="delivery_type" class="form-label">Delivery Type</label>
                            <select class="form-select" id="delivery_type" name="delivery_type" required>
                                <option value="standard">Standard (1-3 days)</option>
                                <option value="express">Express (Same day)</option>
                                <option value="overnight">Overnight</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
                        </div>
                        <div class="col-12">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="special_instructions" name="special_instructions" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="driver_id" class="form-label">Select Driver</label>
                            <select class="form-select" id="driver_id" name="driver_id" required>
                                <option value="" disabled selected>Select a driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">
                                        {{ $driver->fname }} {{ $driver->lname }} - Average Rating: {{ number_format($driver->average_rating, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Submit Delivery Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.getElementById('delivery_date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>