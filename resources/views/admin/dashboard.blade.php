<!DOCTYPE html>

<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body class="d-flex">
    @include('admin.sidebar', ['activePage' => 'dashboard'])

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Admin Dashboard</h2>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Drivers</h5>
                        <p class="display-4">{{ App\Models\Driver::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Approved Drivers</h5>
                        <p class="display-4">{{ App\Models\Driver::where('approved', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pending Drivers</h5>
                        <p class="display-4">{{App\Models\Driver::where('approved', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
