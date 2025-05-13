<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Driver Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .nav-link.active {
            background-color: #0d6efd;
            color: white !important;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body class="d-flex">
    @include('driver.sidebar')

    <div class="main-content bg-light">
        @include('driver.topnav')

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
