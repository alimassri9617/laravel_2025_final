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
            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
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
                            <select class="form-select" id="pickup_location" name="pickup_location" required>
                                <option value="" disabled {{ !isset($pickup_location) ? 'selected' : '' }}>Select Pickup Location</option>
                                <option value="Beirut" {{ (isset($pickup_location) && $pickup_location == 'Beirut') ? 'selected' : '' }}>Beirut</option>
                                <option value="Tripoli" {{ (isset($pickup_location) && $pickup_location == 'Tripoli') ? 'selected' : '' }}>Tripoli</option>
                                <option value="Saida" {{ (isset($pickup_location) && $pickup_location == 'Saida') ? 'selected' : '' }}>Saida</option>
                                <option value="Tyre" {{ (isset($pickup_location) && $pickup_location == 'Tyre') ? 'selected' : '' }}>Tyre</option>
                                <option value="Jounieh" {{ (isset($pickup_location) && $pickup_location == 'Jounieh') ? 'selected' : '' }}>Jounieh</option>
                                <option value="Zahle" {{ (isset($pickup_location) && $pickup_location == 'Zahle') ? 'selected' : '' }}>Zahle</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="destination" class="form-label">Destination</label>
                            <select class="form-select" id="destination" name="destination" required>
                                <option value="" disabled {{ !isset($destination) ? 'selected' : '' }}>Select Destination</option>
                                <option value="Beirut" {{ (isset($destination) && $destination == 'Beirut') ? 'selected' : '' }}>Beirut</option>
                                <option value="Tripoli" {{ (isset($destination) && $destination == 'Tripoli') ? 'selected' : '' }}>Tripoli</option>
                                <option value="Saida" {{ (isset($destination) && $destination == 'Saida') ? 'selected' : '' }}>Saida</option>
                                <option value="Tyre" {{ (isset($destination) && $destination == 'Tyre') ? 'selected' : '' }}>Tyre</option>
                                <option value="Jounieh" {{ (isset($destination) && $destination == 'Jounieh') ? 'selected' : '' }}>Jounieh</option>
                                <option value="Zahle" {{ (isset($destination) && $destination == 'Zahle') ? 'selected' : '' }}>Zahle</option>
                            </select>
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
                        <div class="col-12 d-flex justify-content-center">
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

        // Function to fetch filtered drivers based on pickup and destination
        async function fetchFilteredDrivers() {
            const pickup = document.getElementById('pickup_location').value;
            const destination = document.getElementById('destination').value;
            const driverSelect = document.getElementById('driver_id');

            if (!pickup || !destination) {
                // Clear driver options except the placeholder
                driverSelect.innerHTML = '<option value="" disabled selected>Select a driver</option>';
                return;
            }

            try {
                const response = await fetch(`/api/drivers/filter?pickup_location=${encodeURIComponent(pickup)}&destination=${encodeURIComponent(destination)}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const drivers = await response.json();

                // Clear existing options
                driverSelect.innerHTML = '<option value="" disabled selected>Select a driver</option>';

                // Populate new options
                drivers.forEach(driver => {
                    const option = document.createElement('option');
                    option.value = driver.id;
                    const avgRating = Number(driver.average_rating) || 0;
                    option.textContent = `${driver.fname} ${driver.lname} - Average Rating: ${avgRating.toFixed(2)}`;
                    driverSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching drivers:', error);
            }
        }

        // Add event listeners to pickup and destination selects
        document.getElementById('pickup_location').addEventListener('change', fetchFilteredDrivers);
        document.getElementById('destination').addEventListener('change', fetchFilteredDrivers);

        // Initial fetch if both locations are pre-selected
        window.addEventListener('DOMContentLoaded', () => {
            fetchFilteredDrivers();
        });
    </script>
</body>
</html>
