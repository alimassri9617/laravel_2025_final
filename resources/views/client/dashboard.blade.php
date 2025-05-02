<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    @if (session('success'))
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
                                    <a href="{{ route('client.chat.show', $delivery->id) }}" class="btn btn-success">
                                        <i class="fas fa-comments"></i> Chat with Driver
                                    </a>
                                   </td>
                                   <td>
                                    @if($delivery->status == 'completed' && !in_array($delivery->id, $reviewedDeliveryIds))
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $delivery->id }}">
                                        <i class="fas fa-star"></i> Review Driver
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="reviewModal{{ $delivery->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $delivery->id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <form method="POST" action="{{ route('client.review.submit', $delivery->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="reviewModalLabel{{ $delivery->id }}">Review Driver for Delivery #{{ $delivery->id }}</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                              <div class="mb-3">
                                                <label for="rating{{ $delivery->id }}" class="form-label">Rating (1-5 stars)</label>
                                                <select class="form-select" id="rating{{ $delivery->id }}" name="rating" required>
                                                  <option value="">Select rating</option>
                                                  <option value="1">1 Star</option>
                                                  <option value="2">2 Stars</option>
                                                  <option value="3">3 Stars</option>
                                                  <option value="4">4 Stars</option>
                                                  <option value="5">5 Stars</option>
                                                </select>
                                              </div>
                                              <div class="mb-3">
                                                <label for="comment{{ $delivery->id }}" class="form-label">Comment</label>
                                                <textarea class="form-control" id="comment{{ $delivery->id }}" name="comment" rows="3" placeholder="Write your review here..."></textarea>
                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                              <button type="submit" class="btn btn-primary">Submit Review</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    @endif
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
   
</body>
</html>