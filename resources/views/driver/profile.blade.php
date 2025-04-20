<!DOCTYPE html>
<html>
<head>
    <title>Driver Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="d-flex">
    <!-- Sidebar (same as dashboard) -->
    

    <!-- Main Content -->
    <div class="main-content bg-light">
        <!-- Top Navigation (same as dashboard) -->
       

        <!-- Profile Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Profile Settings</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            
                            <img src="{{ asset('driver_images/' . $driver->image) }}" class="rounded-circle mb-3" width="150" height="150">

                           
                            <h4>{{ $driver->fname }} {{ $driver->lname }}</h4>
                            <p class="text-muted">{{ $driver->vehicle_type }} Driver</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Edit Profile</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('driver.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="fname" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="{{ $driver->fname }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="{{ $driver->lname }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $driver->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $driver->phone }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                        <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                            <option value="car" {{ $driver->vehicle_type == 'car' ? 'selected' : '' }}>Car</option>
                                            <option value="motorcycle" {{ $driver->vehicle_type == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                            <option value="van" {{ $driver->vehicle_type == 'van' ? 'selected' : '' }}>Van</option>
                                            <option value="truck" {{ $driver->vehicle_type == 'truck' ? 'selected' : '' }}>Truck</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="plate_number" class="form-label">Plate Number</label>
                                        <input type="text" class="form-control" id="plate_number" name="plate_number" value="{{ $driver->plate_number }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="work_area" class="form-label">Working Areas</label>
                                        <div class="row">
                                            @php
                                                $workAreas = explode(',', $driver->work_area);
                                            @endphp
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_beirut" name="work_area[]" value="Beirut" {{ in_array('Beirut', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_beirut">Beirut</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_tripoli" name="work_area[]" value="Tripoli" {{ in_array('Tripoli', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_tripoli">Tripoli</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_saida" name="work_area[]" value="Saida" {{ in_array('Saida', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_saida">Saida</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_tyre" name="work_area[]" value="Tyre" {{ in_array('Tyre', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_tyre">Tyre</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_jounieh" name="work_area[]" value="Jounieh" {{ in_array('Jounieh', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_jounieh">Jounieh</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="area_zahle" name="work_area[]" value="Zahle" {{ in_array('Zahle', $workAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area_zahle">Zahle</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>