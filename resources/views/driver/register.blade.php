<!DOCTYPE html>
<html>
<head>
    <title>Driver Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-check-label { margin-left: 5px; }
        .password-toggle { cursor: pointer; position: absolute; right: 10px; top: 10px; }
        .password-wrapper { position: relative; }
        .card { max-width: 800px; margin: 0 auto; }
        .section-title { border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 20px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-user-plus me-2"></i>Driver Registration</h4>
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                                <h5><i class="fas fa-exclamation-circle me-2"></i>Validation Errors</h5>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('driver.register') }}" enctype="multipart/form-data" id="registrationForm">
                            @csrf
                            
                            <h5 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" value="{{ old('fname') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="{{ old('lname') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                        <i class="far fa-eye password-toggle" onclick="togglePassword('password')"></i>
                                    </div>
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
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
                                    <input type="text" class="form-control" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="driver_license" class="form-label">Driver License Number</label>
                                    <input type="text" class="form-control" id="driver_license" name="driver_license" value="{{ old('driver_license') }}" required>
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
                                            <input class="form-check-input" type="checkbox" id="area_beirut" name="work_area[]" value="Beirut" {{ in_array('Beirut', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_beirut">Beirut</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="area_tripoli" name="work_area[]" value="Tripoli" {{ in_array('Tripoli', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_tripoli">Tripoli</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="area_saida" name="work_area[]" value="Saida" {{ in_array('Saida', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_saida">Saida</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="area_tyre" name="work_area[]" value="Tyre" {{ in_array('Tyre', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_tyre">Tyre</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="area_jounieh" name="work_area[]" value="Jounieh" {{ in_array('Jounieh', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_jounieh">Jounieh</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="area_zahle" name="work_area[]" value="Zahle" {{ in_array('Zahle', old('work_area', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="area_zahle">Zahle</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="text-muted">Max 2MB (JPEG, PNG, JPG)</small>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a></label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            Already have an account? <a href="{{ route('driver.login') }}">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- @include('partials.terms') <!-- Create a terms.blade.php with your terms --> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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

        // Validate password match
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            const checkboxes = document.querySelectorAll('input[name="work_area[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one work area!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>