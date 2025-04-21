<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard</title>
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
    {{@if session('success')}}
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard</h2>
            <a href="{{ route('client.deliveries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Delivery
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Deliveries</h5>
            </div>
            <div class="card-body">
                @if($deliveries->isEmpty())
                    <div class="alert alert-info">You have no deliveries yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pickup</th>
                                    <th>Destination</th>
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
                                    <td>
                                        <span class="badge bg-{{ 
                                            $delivery->status == 'completed' ? 'success' : 
                                            ($delivery->status == 'pending' ? 'warning' : 'primary')
                                        }}">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('client.deliveries.show', $delivery->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                   <td>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#chatModal">
                                        <i class="fas fa-comments"></i> Chat with Driver
                                    </button>
                                    
                                    <!-- Chat Modal -->
                                    <div class="modal fade" id="chatModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chat</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="chatMessages" style="height: 400px; overflow-y: auto; margin-bottom: 20px;">
                                                        <!-- Messages will be loaded here -->
                                                    </div>
                                                    <form id="chatForm" action="{{ route('client.chat.store') }}" method="POST"></form>
                                                        
                                                        @csrf
                                                        <input type="hidden" name="sender_type" value="client">
                                                        <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
                                                        <input type="hidden" name="sender_id" value="{{ $delivery->driver_id }}">
                                                        <div class="input-group">
                                                            <textarea class="form-control" name="message" placeholder="Type your message"></textarea>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
   //test
</body>
</html>