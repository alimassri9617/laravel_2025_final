<!DOCTYPE html>
<html>
<head>
    <title>Driver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            width: 250px;
        }
        .main-content {
            flex: 1;
        }
        .badge-status {
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-white p-3">
        <div class="sidebar-header mb-4">
            <h4>DeliveryApp</h4>
            <p class="text-muted mb-0">Driver Dashboard</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white active" href="{{ route('driver.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('driver.available-deliveries') }}">
                    <i class="fas fa-list-alt me-2"></i> Available Deliveries
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('driver.earnings') }}">
                    <i class="fas fa-money-bill-wave me-2"></i> Earnings
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('driver.profile') }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link text-white" href="{{ route('driver.logout') }}">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content bg-light">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">
                        Welcome, {{ $driver->fname }} {{ $driver->lname }}
                    </span>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="container-fluid p-4">
            <h2 class="mb-4">Dashboard</h2>

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

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Deliveries</h5>
                </div>
                <div class="card-body">
                    @if($deliveries->isEmpty())
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deliveries as $delivery)
                                    <tr>
                                        <td>#{{ $delivery->id }}</td>
                                        <td>{{ $delivery->pickup_location }}</td>
                                        <td>{{ $delivery->destination }}</td>
                                        <td>${{ number_format($delivery->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-status bg-{{ 
                                                $delivery->status == 'completed' ? 'success' : 
                                                ($delivery->status == 'pending' ? 'warning' : 'primary')
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
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