<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery App - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .registration-type {
            cursor: pointer;
            transition: all 0.3s;
        }
        .registration-type:hover {
            transform: translateY(-5px);
        }
        .registration-type.active {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
        }
        #driverForm {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.html">DeliveryApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light" href="{{route('register')}}">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center mb-4">Create Your Account</h2>
                            
                            <!-- Registration Type Selection -->
                            <div class="row mb-4 g-3">
                                <div class="col-md-6">
                                    <div class="registration-type card border p-4 text-center h-100 active" id="clientType">
                                        <i class="fas fa-user fa-3x text-primary mb-3"></i>
                                        <h4>Register as Client</h4>
                                        <p class="text-muted">I want to request delivery services</p>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="userType" id="clientRadio" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="registration-type card border p-4 text-center h-100" id="driverType">
                                        <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                                        <h4>Register as Driver</h4>
                                        <p class="text-muted">I want to provide delivery services</p>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="radio" name="userType" id="driverRadio">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Client Registration Form -->
                            <form id="clientForm" method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                                @csrf
                                <h5 class="mb-4">Client Information</h5>
                                <div class="row g-3">
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <label for="clientFirstName" class="form-label">First Name</label>
                                        <input name="fname" type="text" class="form-control" id="clientFirstName" placeholder="Enter your first name" required>
                                    </div>
                            
                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <label for="clientLastName" class="form-label">Last Name</label>
                                        <input name="lname" type="text" class="form-control" id="clientLastName" placeholder="Enter your last name" required>
                                    </div>
                            
                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="clientEmail" class="form-label">Email Address</label>
                                        <input name="email" type="email" class="form-control" id="clientEmail" placeholder="Enter your email" required>
                                    </div>
                            
                                    <!-- Phone Number -->
                                    <div class="col-md-6">
                                        <label for="clientPhone" class="form-label">Phone Number</label>
                                        <input name="phone" type="tel" class="form-control" id="clientPhone" placeholder="Enter your phone number" required>
                                    </div>
                            
                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <label for="clientPassword" class="form-label">Password</label>
                                        <input name="password" type="password" class="form-control" id="clientPassword" placeholder="Enter your password" required>
                                    </div>
                            
                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <label for="clientConfirmPassword" class="form-label">Confirm Password</label>
                                        <input name="password_confirmation" type="password" class="form-control" id="clientConfirmPassword" placeholder="Confirm your password" required>
                                    </div>
                            
                                    <!-- Profile Picture -->
                                    <div class="col-md-6">
                                        <label for="clientProfilePicture" class="form-label">Profile Picture</label>
                                        <input name="image" type="file" class="form-control" id="clientProfilePicture" accept="image/*" required>
                                    </div>
                            
                                    <!-- Terms and Conditions -->
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input name="terms" class="form-check-input" type="checkbox" id="clientTerms" required>
                                            <label class="form-check-label" for="clientTerms">
                                                I agree to the <a href="#">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>
                            
                                    <!-- Submit Button -->
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary w-100">Register as Client</button>
                                    </div>
                                </div>
                            </form>
                            

                            <!-- Driver Registration Form -->
                            <form id="driverForm" method="POST" enctype="multipart/form-data" action="{{ route('register.storedriver') }}">
                                @csrf
                                <h5 class="mb-4">Driver Information</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="driverFirstName" class="form-label">First Name</label>
                                        <input name="fname" type="text" class="form-control" id="driverFirstName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverLastName" class="form-label">Last Name</label>
                                        <input name="lname" type="text" class="form-control" id="driverLastName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverEmail" class="form-label">Email Address</label>
                                        <input name="email" type="email" class="form-control" id="driverEmail" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverPhone" class="form-label">Phone Number</label>
                                        <input name="phone" type="tel" class="form-control" id="driverPhone" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverPassword" class="form-label">Password</label>
                                        <input name="password" type="password" class="form-control" id="driverPassword" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverConfirmPassword" class="form-label">Confirm Password</label>
                                        <input name="password_confirmation" type="password" class="form-control" id="driverConfirmPassword" required>
                                    </div>
                                    
                                    <!-- Driver Specific Fields -->
                                    <div class="col-md-6">
                                        <label for="driverVehicleType" class="form-label">Vehicle Type</label>
                                        <select name="vicheltype" class="form-select" id="driverVehicleType" required>
                                            <option value="">Select Vehicle</option>
                                            <option value="motorcycle">Motorcycle</option>
                                            <option value="car">Car</option>
                                            <option value="van">Van</option>
                                            <option value="truck">Truck</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverPlateNumber" class="form-label">Plate Number</label>
                                        <input name="platenumber" type="text" class="form-control" id="driverPlateNumber" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverLicenseNumber" class="form-label">Driver License Number</label>
                                        <input name="driverlicense" type="text" class="form-control" id="driverLicenseNumber" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverPricingModel" class="form-label">Pricing Model</label>
                                        <select name="pricemodel" class="form-select" id="driverPricingModel" required>
                                            <option value="">Select Pricing</option>
                                            <option value="fixed">Fixed Price per Delivery</option>
                                            <option value="per_km">Per Kilometer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverWorkArea" class="form-label">Working Area</label>
                                        <select name="work_area" class="form-select" id="driverWorkArea">
                                            <option value="baalbek" selected>Baalbek</option>
                                            <option value="beirut">Beirut</option>
                                            <option value="tripoli">Tripoli</option>
                                            <option value="saida">Saida</option>
                                            <option value="tyre">Tyre</option>
                                            <option value="jounieh">Jounieh</option>
                                            <option value="zahle">Zahle</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input name="terms" class="form-check-input" type="checkbox" id="driverTerms" required>
                                            <label class="form-check-label" for="driverTerms">
                                                I agree to the <a href="#">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="background_check" class="form-check-input" type="checkbox" id="driverBackgroundCheck" required>
                                            <label class="form-check-label" for="driverBackgroundCheck">
                                                I consent to a background check
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driverProfilePicture" class="form-label">Profile Picture</label>
                                        <input name="image" type="file" class="form-control" id="driverProfilePicture" accept="image/*" required>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary w-100">Register as Driver</button>
                                    </div>
                                </div>
                            </form>

                            <hr class="my-4">
                            <div class="text-center">
                                <p class="mb-0">Already have an account? <a href="{{route('login')}}" class="text-primary">Login</a></p>
                            </div>
                            <div class="social-login mt-4">
                                <p class="text-center mb-3">Or register with</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="#" class="btn btn-outline-primary"><i class="fab fa-google"></i> Google</a>
                                    <a href="#" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Facebook</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 DeliveryApp. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle between client and driver registration forms
            const clientType = document.getElementById('clientType');
            const driverType = document.getElementById('driverType');
            const clientForm = document.getElementById('clientForm');
            const driverForm = document.getElementById('driverForm');
            const clientRadio = document.getElementById('clientRadio');
            const driverRadio = document.getElementById('driverRadio');

            clientType.addEventListener('click', function() {
                clientType.classList.add('active');
                driverType.classList.remove('active');
                clientForm.style.display = 'block';
                driverForm.style.display = 'none';
                clientRadio.checked = true;
            });

            driverType.addEventListener('click', function() {
                driverType.classList.add('active');
                clientType.classList.remove('active');
                driverForm.style.display = 'block';
                clientForm.style.display = 'none';
                driverRadio.checked = true;
            });

            // Form validation for client registration
            const clientRegisterForm = document.getElementById('clientForm');
            if (clientRegisterForm) {
                clientRegisterForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('clientPassword').value;
                    const confirmPassword = document.getElementById('clientConfirmPassword').value;
                    
                    if (password !== confirmPassword) {
                        alert('Passwords do not match');
                        e.preventDefault();
                        return;
                    }
                    
                    if (!document.getElementById('clientTerms').checked) {
                        alert('You must agree to the terms and conditions');
                        e.preventDefault();
                        return;
                    }
                });
            }

            // Form validation for driver registration
            const driverRegisterForm = document.getElementById('driverForm');
            if (driverRegisterForm) {
                driverRegisterForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('driverPassword').value;
                    const confirmPassword = document.getElementById('driverConfirmPassword').value;
                    
                    if (password !== confirmPassword) {
                        alert('Passwords do not match');
                        e.preventDefault();
                        return;
                    }
                    
                    if (!document.getElementById('driverTerms').checked) {
                        alert('You must agree to the terms and conditions');
                        e.preventDefault();
                        return;
                    }
                    
                    if (!document.getElementById('driverBackgroundCheck').checked) {
                        alert('You must consent to a background check');
                        e.preventDefault();
                        return;
                    }
                });
            }
        });
    </script>
</body>
</html>