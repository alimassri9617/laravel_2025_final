<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeliveryApp - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">DeliveryApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('driver.login') }}">Driver Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('client.login') }}">Client Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-4 fw-bold">Fast & Reliable Delivery Services</h1>
                <p class="lead text-muted mb-4">Connect with trusted drivers. Real-time tracking, secure payments, and professional service.</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register</a>
                    <a href="#how-it-works" class="btn btn-outline-primary btn-lg">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0 text-center">
<img src="{{ asset('delivery-man.png') }}" alt="Delivery" class="img-fluid" width="200" height="100">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Why Choose Us</h2>
        <div class="row g-4">
            <div class="col-md-4">
            <div class="card text-center border-0 shadow-lg h-100">
                    <div class="card-body">
                        <div class="mb-3 text-primary"><i class="fas fa-map-marker-alt fa-3x"></i></div>
                        <h5 class="card-title fw-semibold">Live Tracking</h5>
                        <p class="card-text">Follow your package in real-time with accurate GPS updates.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="card text-center border-0 shadow-lg h-100">
                    <div class="card-body">
                        <div class="mb-3 text-primary"><i class="fas fa-shield-alt fa-3x"></i></div>
                        <h5 class="card-title fw-semibold">Secure Payments</h5>
                        <p class="card-text">Choose from credit card, crypto, or cash on delivery options.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="card text-center border-0 shadow-lg h-100">
                    <div class="card-body">
                        <div class="mb-3 text-primary"><i class="fas fa-headset fa-3x"></i></div>
                        <h5 class="card-title fw-semibold">24/7 Support</h5>
                        <p class="card-text">Get help whenever you need it with our dedicated support team.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">How It Works</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-lg">
                    <div class="fs-3 fw-bold text-primary mb-3">1</div>
                    <h5 class="fw-semibold">Create Account</h5>
                    <p>Register easily as a driver or client in a few small steps.</p>
                </div>
            </div>
            <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-lg">
                    <div class="fs-3 fw-bold text-primary mb-3">2</div>
                    <h5 class="fw-semibold">Request Delivery</h5>
                    <p>Fill in delivery details and auto-assign or choose a driver.</p>
                </div>
            </div>
            <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-lg">
                    <div class="fs-3 fw-bold text-primary mb-3">3</div>
                    <h5 class="fw-semibold">Track & Receive</h5>
                    <p>Track deliveries in real-time and receive items quickly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">DeliveryApp</h5>
                <p>Connecting clients with reliable drivers for secure deliveries.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold">Links</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="#features" class="text-white text-decoration-none">Features</a></li>
                    <li><a href="#how-it-works" class="text-white text-decoration-none">How It Works</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold">Contact</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i> support@deliveryapp.com</li>
                    <li><i class="fas fa-phone me-2"></i> +961 1 234 567</li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold">Follow Us</h6>
                <div class="d-flex justify-content-center justify-content-md-start gap-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center small">&copy; 2024 DeliveryApp. All rights reserved.</div>
    </div>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Optional JS -->
<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
