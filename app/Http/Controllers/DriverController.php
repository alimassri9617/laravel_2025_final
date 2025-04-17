<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('driver.register');
    }


    protected function checkDriverSession()
{
    if (!Session::has('driver_id')) {
        return redirect()->route('driver.login');
    }
    return null;
}
    // Handle registration
    public function register(Request $request)
    {
       $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers',
            'phone' => 'required|string|max:20|unique:drivers',
            'password' => 'required|string|min:8|confirmed',
            'vehicle_type' => 'required|in:car,motorcycle,van,truck',
            'plate_number' => 'required|string|max:20',
            'work_area' => 'required|array',
            'work_area.*' => 'string',
            'image' => 'nullable|image|max:2048'
        ]);
        $data = $request->only([
            'fname', 'lname', 'email', 'phone',
            'vehicle_type', 'plate_number'
        ]);
        $data['work_area'] = implode(',', $request->work_area);
        $data['password'] = password_hash($request->password, PASSWORD_BCRYPT);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('driver_images', 'public');
            $data['image'] = $path;
        }
        $data['approved'] = false; // Set to false by default
        $data['driver_license'] = $request->input('driver_license');
        $data['price_model'] = $request->input('price_model');
        $driver = Driver::create($data);
        // Send email to admin for approval


        return redirect()->route('driver.login')->with('success', 'Registration submitted! Your account will be reviewed by our team.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('driver.login');
    }
    

   
    public function login(Request $request)

    
    { 



        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        $driver = Driver::where('email', $request->email)->first();

       

        Session::put('driver_id', $driver->id);
        Session::put('driver_name', $driver->fname . ' ' . $driver->lname);

        return redirect()->route('driver.dashboard');
        
    
        
       
    }
    // Handle logout
    public function logout()
    {
        Session::forget(['driver_id', 'driver_name']);
        return redirect()->route('driver.login');
    }

    // Dashboard
   public function dashboard()
{
    if ($redirect = $this->checkDriverSession()) {
        return $redirect;
    }

    $driverId = Session::get('driver_id');
    $driver = Driver::findOrFail($driverId);
    $deliveries = Delivery::where('driver_id', $driverId)
                        ->orderBy('created_at', 'desc')
                        ->get();
    // Get the total number of deliveries
    return view('driver.dashboard', [
        'driver' => $driver,
        'deliveries' => $deliveries
    ]);
}

    // Show available deliveries
   // Remove redundant session checks since checkDriverSession() handles it
   public function availableDeliveries()
   {
       if ($redirect = $this->checkDriverSession()) {
           return $redirect;
       }
   
       $driver = Driver::find(Session::get('driver_id'));
       
       // Get deliveries that:
       // 1. Have no driver assigned (NULL driver_id)
       // 2. Are in pending status
       // 3. Match the driver's work area
       $deliveries = Delivery::whereNull('driver_id')
                           ->where('status', 'pending')
                           ->where(function($query) use ($driver) {
                               if ($driver->work_area) {
                                   $areas = explode(',', $driver->work_area);
                                   foreach ($areas as $area) {
                                       $query->orWhere('pickup_location', 'like', "%$area%")
                                             ->orWhere('destination', 'like', "%$area%");
                                   }
                               }
                           })
                           ->with('client') // Eager load the client relationship
                           ->orderBy('created_at', 'desc')
                           ->get();
   
       return view('driver.available-deliveries', [
           'driver' => $driver,
           'deliveries' => $deliveries
       ]);
   }
    // Accept a delivery
    public function acceptDelivery($id)
{
    if ($redirect = $this->checkDriverSession()) {
        return $redirect;
    }

    $delivery = Delivery::findOrFail($id);
    $driverId = Session::get('driver_id');

    // Additional validation
    if ($delivery->driver_id || $delivery->status != 'pending') {
        return back()->with('error', 'Delivery no longer available');
    }

    $delivery->update([
        'driver_id' => $driverId,
        'status' => 'accepted'
    ]);

    return back()->with('success', 'Delivery accepted successfully!');
}

    // Update delivery status
    public function updateDeliveryStatus(Request $request, $id)
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        $request->validate([
            'status' => 'required|in:in_progress,completed,cancelled'
        ]);

        $delivery = Delivery::where('id', $id)
                          ->where('driver_id', Session::get('driver_id'))
                          ->firstOrFail();

        $delivery->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully!');
    }

    // Earnings report
    public function earnings()
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        $driverId = Session::get('driver_id');
        $completedDeliveries = Delivery::where('driver_id', $driverId)
                                    ->where('status', 'completed')
                                    ->get();

        $totalEarnings = $completedDeliveries->sum('amount');
        $pendingEarnings = Delivery::where('driver_id', $driverId)
                                ->where('status', 'in_progress')
                                ->sum('amount');

        return view('driver.earnings', [
            'driver' => Driver::find($driverId),
            'completedDeliveries' => $completedDeliveries,
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings
        ]);
    }

    // Profile settings
    public function showProfile()
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        return view('driver.profile', [
            'driver' => Driver::find(Session::get('driver_id'))
        ]);
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        $driver = Driver::find(Session::get('driver_id'));

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers,email,'.$driver->id,
            'phone' => 'required|string|max:20|unique:drivers,phone,'.$driver->id,
            'vehicle_type' => 'required|in:car,motorcycle,van,truck',
            'plate_number' => 'required|string|max:20',
            'work_area' => 'required|array',
            'work_area.*' => 'string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'fname', 'lname', 'email', 'phone',
            'vehicle_type', 'plate_number'
        ]);
        $data['work_area'] = implode(',', $request->work_area);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('driver_images', 'public');
            $data['image'] = $path;
        }

        $driver->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}