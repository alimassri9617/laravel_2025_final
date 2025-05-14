<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\FcmServiceV1;
use App\Notifications\DeliveryAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;

class DriverController extends Controller
{
   
    protected $fcmService;

    public function __construct(FcmServiceV1 $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    // Save or update FCM token for the driver
    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $driverId = Session::get('driver_id');
        if (!$driverId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $driver = Driver::find($driverId);
        if (!$driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        $driver->fcm_token = $request->fcm_token;
        $driver->save();

        return response()->json(['message' => 'FCM token saved successfully']);
    }
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
           //i went to store the image in public folder not in storage
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

       $driverapproved=Driver::where('email', $request->email)->where('approved', true)->first();
        if (!$driverapproved) {
            return back()->with('error', 'Your account is not approved yet, or you entered wrong credentials.');
        }

        Session::put('driver_id', $driver->id);
        Session::put('driver_name', $driver->fname . ' ' . $driver->lname);

        return redirect()->route('driver.dashboard');
        
    
        
       
    }
    // Handle logout
    public function logout()
    {
        Session::forget(['driver_id', 'driver_name']);
        return redirect('/');
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

    $notifications = \App\Models\DriverNotification::where('driver_id', $driverId)
                        ->orderBy('created_at', 'desc')
                        ->get();

    // Get the total number of deliveries
    return view('driver.dashboard', [
        'driver' => $driver,
        'deliveries' => $deliveries,
        'notifications' => $notifications,
        'activePage' => 'dashboard',
    ]);
}

    // Show available deliveries
   // Remove redundant session checks since checkDriverSession() handles it
   public function availableDeliveries()
   {
       if ($redirect = $this->checkDriverSession()) {
           return $redirect;
       }
   
       $driverId = Session::get('driver_id');
       $driver = Driver::find($driverId);
       
       // Get deliveries assigned to this driver that are pending or not yet accepted
       $deliveries = Delivery::where('driver_id', $driverId)
                             ->whereIn('status', ['pending'])
                             ->orderBy('created_at', 'desc')
                             ->get();

       // Get recent deliveries for this driver (all deliveries ordered by created_at desc)
       $recentDeliveries = Delivery::where('driver_id', $driverId)
                                  ->orderBy('created_at', 'desc')
                                  ->get();
   
       return view('driver.available-deliveries', [
           'driver' => $driver,
           'deliveries' => $deliveries,
           'recentDeliveries' => $recentDeliveries,
           'activePage' => 'available-deliveries',
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

        // Updated validation: check if delivery is assigned to this driver and status is pending
        if ($delivery->driver_id != $driverId || $delivery->status != 'pending') {
            return back()->with('error', 'Delivery no longer available');
        }

        $delivery->update([
            'status' => 'accepted'
        ]);

        return back()->with('success', 'Delivery accepted successfully!');
        
    }

    // Reject a delivery
    public function rejectDelivery($id)
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }

        $delivery = Delivery::findOrFail($id);
        $driverId = Session::get('driver_id');

        // Ensure the delivery is assigned to this driver and is pending
        if ($delivery->driver_id != $driverId || $delivery->status != 'pending') {
            return back()->with('error', 'Delivery cannot be rejected.');
        }

        // Since 'rejected' is not an allowed status, use 'cancelled' instead
        $delivery->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Delivery rejected successfully.');
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
            'pendingEarnings' => $pendingEarnings,
            'activePage' => 'earnings',
        ]);
    }
    public function delivaryDone(Request $request, $id)
    {
        if ($redirect = $this->checkDriverSession()) {
            return $redirect;
        }
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        $delivery = Delivery::findOrFail($id);
        $delivery->update(['status' => 'completed']);

        return back()->with('success', 'Delivery marked as completed!');
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

        $driver = Driver::find(Session::get('driver_id'));

        return view('driver.profile', [
            'driver' => $driver,
            'activePage' => 'profile',
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
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'fname', 'lname', 'email', 'phone',
            'vehicle_type', 'plate_number', 'is_available'
        ]);
        $data['work_area'] = implode(',', $request->work_area);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('driver_images', 'public');
            $data['image'] = $path;
        }

        $driver->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
    public function markAsComplete(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->status = 'completed';
         $delivery->save();

    return redirect()->back()->with('success', 'Delivery marked as complete!');
    }

    public function chat($delivery)
    {
        // Convert the parameter to a model if it's an ID
        if (!$delivery instanceof \App\Models\Delivery) {
            $delivery = \App\Models\Delivery::findOrFail($delivery);
        }
        
        // Make sure this driver is assigned to this delivery
        if ($delivery->driver_id != session('driver_id')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get the client information
        $client = \App\Models\Client::find($delivery->client_id);
        
        // Get all messages for this delivery
        $messages = \App\Models\Message::where('delivery_id', $delivery->id)
                                       ->orderBy('created_at')
                                       ->get();
        
        return view('driver.chat', compact('delivery', 'client', 'messages'));
    }

      public function calendar()
    {
        if (!Session::has('driver_id')) {
            return redirect()->route('driver.login');
        }

        $driverId = Session::get('driver_id');
        $calendarEvents = \App\Models\CalendarEvent::where('user_id', $driverId)
            ->where('user_type', \App\Models\Driver::class)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('driver.calendar', [
            'driverName' => Session::get('driver_name'),
            'calendarEvents' => $calendarEvents
        ]);
    }
}

