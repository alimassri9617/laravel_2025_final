<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delivery App - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .form-check-label {
            margin-left: 5px;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .password-wrapper {
            position: relative;
        }
        .card {
            max-width: 800px;
            margin: 0 auto;
        }
        .section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
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
            <a class="navbar-brand" href="{{ route('home') }}">DeliveryApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-light" href="{{ route('register') }}">Register</a></li>
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
                                    <div class="col-md-6">
                                        <label for="clientFirstName" class="form-label">First Name</label>
                                        <input name="fname" type="text" class="form-control" id="clientFirstName" placeholder="Enter your first name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientLastName" class="form-label">Last Name</label>
                                        <input name="lname" type="text" class="form-control" id="clientLastName" placeholder="Enter your last name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientEmail" class="form-label">Email Address</label>
                                        <input name="email" type="email" class="form-control" id="clientEmail" placeholder="Enter your email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientPhone" class="form-label">Phone Number</label>
                                        <input name="phone" type="tel" class="form-control" id="clientPhone" placeholder="Enter your phone number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientPassword" class="form-label">Password</label>
                                        <input name="password" type="password" class="form-control" id="clientPassword" placeholder="Enter your password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientConfirmPassword" class="form-label">Confirm Password</label>
                                        <input name="password_confirmation" type="password" class="form-control" id="clientConfirmPassword" placeholder="Confirm your password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="clientProfilePicture" class="form-label">Profile Picture</label>
                                        <input name="image" type="file" class="form-control" id="clientProfilePicture" accept="image/*" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input name="terms" class="form-check-input" type="checkbox" id="clientTerms" required>
                                            <label class="form-check-label" for="clientTerms">
                                                I agree to the <a href="#">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary w-100">Register as Client</button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Driver Registration Form -->
                            <form method="POST" action="{{ route('register.storedriver') }}" enctype="multipart/form-data" id="driverForm" style="display:none;">
                                @csrf
                                
                                <h5 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="fname" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="{{ old('fname') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="{{ old('lname') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="password" name="password" required minlength="8" />
                                            <i class="far fa-eye password-toggle" onclick="togglePassword('password')"></i>
                                        </div>
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required />
                                            <i class="far fa-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="section-title"><i class="fas fa-car me-2"></i>Vehicle Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                        <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                            <option value="">Select Vehicle Type</option>
                                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                            <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="plate_number" class="form-label">Plate Number</label>
                                        <input type="text" class="form-control" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="driver_license" class="form-label">Driver License Number</label>
                                        <input type="text" class="form-control" id="driver_license" name="driver_license" value="{{ old('driver_license') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price_model" class="form-label">Pricing Model</label>
                                        <select class="form-select" id="price_model" name="price_model" required>
                                            <option value="">Select Pricing Model</option>
                                            <option value="fixed" {{ old('price_model') == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                            <option value="per_km" {{ old('price_model') == 'per_km' ? 'selected' : '' }}>Per Kilometer</option>
                                        </select>
                                    </div>
                                </div>

                                <h5 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>Working Areas</h5>
                                <div class="mb-4">
                                    <p>Select areas where you're willing to work:</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_beirut" name="work_area[]" value="Beirut" {{ in_array('Beirut', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_beirut">Beirut</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_tripoli" name="work_area[]" value="Tripoli" {{ in_array('Tripoli', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_tripoli">Tripoli</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_saida" name="work_area[]" value="Saida" {{ in_array('Saida', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_saida">Saida</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_tyre" name="work_area[]" value="Tyre" {{ in_array('Tyre', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_tyre">Tyre</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_jounieh" name="work_area[]" value="Jounieh" {{ in_array('Jounieh', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_jounieh">Jounieh</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="area_zahle" name="work_area[]" value="Zahle" {{ in_array('Zahle', old('work_area', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="area_zahle">Zahle</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                                    <small class="text-muted">Max 2MB (JPEG, PNG, JPG)</small>
                                </div>

                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required />
                                    <label class="form-check-label" for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a></label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </button>
                                </div>
                            </form>

                            <hr class="my-4" />
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
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentElement.querySelector('.password-toggle');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

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
        });
    </script>
</body>
</html>
