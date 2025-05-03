<!DOCTYPE html>
<html>
<head>
    <title>Driver Dashboard</title>
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

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

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
                                        <th>completed</th>
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
                

            <!-- Notifications Section -->
            <div class="mt-4">
                <h4>Notifications</h4>
                @if($notifications->isEmpty())
                    <p>No notifications</p>
                @else
                    <ul class="list-group">
                        @foreach($notifications as $notification)
                            <li class="list-group-item {{ $notification->read ? 'list-group-item-secondary' : '' }}">
                                <strong>{{ $notification->title }}</strong><br>
                                {{ $notification->body }}<br>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-messaging-compat.js"></script>

    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCeExqfPBn-waAHIdGVs1fc7rnVhRvTxTM",
            authDomain: "drivenotify-668ec.firebaseapp.com",
            projectId: "drivenotify-668ec",
            storageBucket: "drivenotify-668ec.firebasestorage.app",
            messagingSenderId: "533476272756",
            appId: "1:533476272756:web:1c1eec250528517ab0bf21",
            measurementId: "G-NTBP5WD0WV"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function getTokenAndSend() {
            console.log('Checking notification permission:', Notification.permission);
            messaging.getToken({ vapidKey: 'BJFtevmdLx8OIxfx2wIdqDKSdEDQq5B6Ol6w1ouqavGLvBdk_4nC603tyjkfY8mD1M2pFIm_0zTuAwaE2wzxhzg' }).then((currentToken) => {
                if (currentToken) {
                    console.log('FCM Token:', currentToken);
                    // Send token to backend to save
                    console.log('Sending token to backend...');
                    fetch('/driver/fcm-token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ fcm_token: currentToken })
                    })
                    .then(response => {
                        console.log('Fetch response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Token saved:', data);
                    })
                    .catch(error => {
                        console.error('Error saving token:', error);
                    });
                } else {
                    console.log('No registration token available. Request permission to generate one.');
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
            });
        }

        if (Notification.permission === 'granted') {
            getTokenAndSend();
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    getTokenAndSend();
                } else {
                    console.log('Notification permission denied');
                }
            });
        } else {
            console.log('Notification permission denied previously');
        }

        // Handle incoming messages while app is in foreground
        messaging.onMessage(function(payload) {
            console.log('Message received. ', payload);

            // Update toast content
            document.getElementById('toastTitle').textContent = payload.notification.title;
            document.getElementById('toastBody').textContent = payload.notification.body;

            // Show the toast
            var toastEl = document.getElementById('notificationToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    </script>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                You have a new notification.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
