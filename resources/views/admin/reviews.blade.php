<!DOCTYPE html>
<html>
<head>
    <title>Admin - Driver Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            width: 250px;
            background: #343a40;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .nav-link.active {
            background-color: #495057;
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar text-white p-3">
        <h4>Admin Panel</h4>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.drivers') }}">
                    <i class="fas fa-users me-2"></i> Driver Management
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white active" href="{{ route('admin.reviews') }}">
                    <i class="fas fa-star me-2"></i> Driver Reviews
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link text-white" href="{{ route('admin.logout') }}">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Driver Reviews</h2>

        <form method="GET" action="{{ route('admin.reviews') }}" class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="driverSelect" class="col-form-label">Select Driver:</label>
                </div>
                <div class="col-auto">
                    <select id="driverSelect" name="driver_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Select a driver --</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ (isset($selectedDriver) && $selectedDriver->id == $driver->id) ? 'selected' : '' }}>
                                {{ $driver->name }} ({{ $driver->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if (isset($selectedDriver))
            <h4>Reviews for {{ $selectedDriver->name }} ({{ $selectedDriver->email }})</h4>

            @if ($reviews->isEmpty())
                <p>No reviews available for this driver.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Rating</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                            <td>
                                                @if($review->client)
                                                    {{ $review->client->fname }} {{ $review->client->lname }}
                                                @else
                                                    Unknown Client
                                                @endif
                                            </td>
                                <td>{{ $review->rating }}</td>
                                <td>{{ $review->comment }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
