<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\FcmServiceV1;
use App\Models\DriverNotification;

class ClientController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('client.login');
    }
    
    public function calendar()
    {
        if (!\Illuminate\Support\Facades\Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = \Illuminate\Support\Facades\Session::get('client_id');
        $calendarEvents = \App\Models\CalendarEvent::where('user_id', $clientId)
            ->where('user_type', \App\Models\Client::class)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('client.calendar', [
            'clientName' => \Illuminate\Support\Facades\Session::get('client_name'),
            'calendarEvents' => $calendarEvents
        ]);
    }
    

    // Handle login
    
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $client = Client::where('email', $request->email)->first();
    
        if ($client && Hash::check($request->password, $client->password)) {
            Session::put('client_id', $client->id);
            Session::put('client_name', $client->fname . ' ' . $client->lname);
            return redirect()->route('client.dashboard');
        }
    
        return back()->with('error', 'Invalid credentials');
    }
    

    // Handle logout
    public function logout()
    {
        Session::forget(['client_id', 'client_name']);
        return redirect('/');
    }
    public function showRegistrationForm()
    {
        return view('client.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $client = new Client();
        $client->fname = $request->fname;
        $client->lname = $request->lname;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->password = bcrypt($request->password);
        $client->save();

        return redirect()->route('client.login')->with('success', 'Registration successful! Please log in.');
    }
    // Show dashboard
    public function dashboard()
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = Session::get('client_id');
        $deliveries = Delivery::where('client_id', $clientId)->get();

        // Get delivery IDs that have reviews
        $reviewedDeliveryIds = \App\Models\Review::where('client_id', $clientId)
            ->pluck('delivery_id')
            ->toArray();

        return view('client.dashboard', [
            'clientName' => Session::get('client_name'),
            'deliveries' => $deliveries,
            'reviewedDeliveryIds' => $reviewedDeliveryIds
        ]);
    }

    // New method to handle review submission
    public function submitReview(Request $request, $deliveryId)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = Session::get('client_id');

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $delivery = Delivery::findOrFail($deliveryId);

        // Check if delivery belongs to client and is completed
        if ($delivery->client_id != $clientId || $delivery->status != 'completed') {
            return back()->with('error', 'Invalid delivery for review.');
        }

        // Check if review already exists
        $existingReview = \App\Models\Review::where('delivery_id', $deliveryId)
            ->where('client_id', $clientId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this delivery.');
        }

        // Create review
        \App\Models\Review::create([
            'delivery_id' => $deliveryId,
            'client_id' => $clientId,
            'driver_id' => $delivery->driver_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    // Show all deliveries
    public function deliveries()
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $clientId = Session::get('client_id');
        $deliveries = Delivery::where('client_id', $clientId)->get();

        return view('client.deliveries', [
            'clientName' => Session::get('client_name'),
            'deliveries' => $deliveries
        ]);
    }

    // Show single delivery
    public function showDelivery($id)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $delivery = Delivery::findOrFail($id);
        
        // Basic authorization
        if ($delivery->client_id != Session::get('client_id')) {
            abort(403, 'Unauthorized access');
        }

        return view('client.delivery-details', [
            'clientName' => Session::get('client_name'),
            'delivery' => $delivery
        ]);
    }

    // Show new delivery form
    public function createDelivery(Request $request)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $pickup = $request->query('pickup_location');
        $destination = $request->query('destination');

        $query = \App\Models\Driver::where('is_available', true);

        if ($pickup && $destination) {
            $query->where(function ($q) use ($pickup, $destination) {
                $q->where('work_area', 'like', "%$pickup%")
                  ->where('work_area', 'like', "%$destination%");
            });
        }

        $drivers = $query->with('reviews')->get()->map(function ($driver) {
            $driver->average_rating = $driver->averageRating() ?? 0;
            return $driver;
        });

        return view('client.new-delivery', [
            'clientName' => Session::get('client_name'),
            'drivers' => $drivers,
            'pickup_location' => $pickup,
            'destination' => $destination
        ]);
    }

    // API method to filter drivers by pickup and destination locations
    public function filterDrivers(Request $request)
    {
        $pickup = $request->query('pickup_location');
        $destination = $request->query('destination');

        if (!$pickup || !$destination) {
            return response()->json(['error' => 'Pickup and destination are required'], 400);
        }

        $drivers = \App\Models\Driver::where('is_available', true)
            ->where('work_area', 'like', "%$pickup%")
            ->where('work_area', 'like', "%$destination%")
            ->with('reviews')
            ->get()
            ->map(function ($driver) {
                $driver->average_rating = $driver->averageRating() ?? 0;
                return $driver;
            });

        return response()->json($drivers);
    }

    // Store new delivery
    public function storeDelivery(Request $request)
    {
        if (!Session::has('client_id')) {
            return redirect()->route('client.login');
        }

        $request->validate([
            'pickup_location' => 'required',
            'destination' => 'required',
            'package_type' => 'required|in:small,medium,large,extra_large',
            'delivery_type' => 'required|in:standard,express,overnight',
            'delivery_date' => 'required|date',
            'special_instructions' => 'nullable',
            'driver_id' => 'required|exists:drivers,id'
        ]);

        // Calculate delivery date based on delivery type
        $currentDate = \Carbon\Carbon::now();
        switch ($request->delivery_type) {
            case 'express':
                $calculatedDate = $currentDate->copy()->addDays(0);
                break;
            case 'overnight':
                $calculatedDate = $currentDate->copy()->addDays(1);
                break;
            case 'standard':
            default:
                $calculatedDate = $currentDate->copy()->addDays(3);
                break;
        }

        $delivery = new Delivery();
        $delivery->client_id = Session::get('client_id');
        $delivery->pickup_location = $request->pickup_location;
        $delivery->destination = $request->destination;
        $delivery->package_type = $request->package_type;
        $delivery->delivery_type = $request->delivery_type;
        $delivery->delivery_date = $calculatedDate->toDateString();
        $delivery->special_instructions = $request->special_instructions;
        $delivery->driver_id = $request->driver_id;
        $delivery->status = 'pending';
        $delivery->amount = $this->calculateAmount($request->package_type, $request->delivery_type);
        $delivery->save();

        // Create calendar events for client and driver
        \App\Models\CalendarEvent::create([
            'user_id' => $delivery->client_id,
            'user_type' => \App\Models\Client::class,
            'delivery_id' => $delivery->id,
            'event_date' => $delivery->delivery_date,
            'event_title' => 'Delivery: ' . $delivery->pickup_location . ' to ' . $delivery->destination,
            'event_description' => 'Delivery from ' . $delivery->pickup_location . ' to ' . $delivery->destination,
        ]);

        \App\Models\CalendarEvent::create([
            'user_id' => $delivery->driver_id,
            'user_type' => \App\Models\Driver::class,
            'delivery_id' => $delivery->id,
            'event_date' => $delivery->delivery_date,
            'event_title' => 'Delivery: ' . $delivery->pickup_location . ' to ' . $delivery->destination,
            'event_description' => 'Delivery from ' . $delivery->pickup_location . ' to ' . $delivery->destination,
        ]);

        // Notify the assigned driver
        $fcmService = new FcmServiceV1();
        $driver = \App\Models\Driver::find($delivery->driver_id);
        if ($driver) {
            // Save notification in database
            DriverNotification::create([
                'driver_id' => $driver->id,
                'title' => 'New Delivery Assigned',
                'body' => 'You have been assigned a new delivery request. Please check your dashboard.',
                'read' => false,
            ]);

            // Send push notification
            if ($driver->fcm_token) {
                $title = 'New Delivery Assigned';
                $body = 'You have been assigned a new delivery request. Please check your dashboard.';
                $data = [
                    'delivery_id' => (string)$delivery->id,
                    'status' => $delivery->status,
                ];
                $fcmService->sendNotification($driver->fcm_token, $title, $body, $data);
            }
        }

        return redirect()->route('client.dashboard')->with('success', 'Delivery created successfully!');
    }

    // Calculate delivery amount
    private function calculateAmount($packageType, $deliveryType)
    {
        $basePrices = [
            'small' => 5.00,
            'medium' => 10.00,
            'large' => 15.00,
            'extra_large' => 25.00
        ];

        $multipliers = [
            'standard' => 1.0,
            'express' => 1.5,
            'overnight' => 2.0
        ];

        return $basePrices[$packageType] * $multipliers[$deliveryType];
    }

public function chat(Delivery $delivery)
{
       Log::info('Client ID in session: ' . session('client_id'));
    
    // Convert the parameter to a model if it's an ID
    if (!$delivery instanceof \App\Models\Delivery) {
        $delivery = \App\Models\Delivery::findOrFail($delivery);
    }
    
    // Make sure this client owns this delivery
    if ($delivery->client_id != session('client_id')) {
        abort(403, 'Unauthorized action.');
    }
    // Convert the parameter to a model if it's an ID
    
    // Make sure this client owns this delivery

    
    // Get the driver information
    $driver = \App\Models\Driver::find($delivery->driver_id);
    
    // If no driver has been assigned yet
    if (!$driver) {
        return back()->with('error', 'No driver has been assigned to this delivery yet.');
    }
    
    // Get all messages for this delivery
    $messages = \App\Models\Message::where('delivery_id', $delivery->id)
                                   ->orderBy('created_at')
                                   ->get();
    
    return view('client.chat', compact('delivery', 'driver', 'messages'));
}
}