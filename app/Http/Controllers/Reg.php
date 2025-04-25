<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\OtpMail;

class Reg extends Controller
{
    public function storeUser(Request $req)
    {
        $req->validate([
            "fname" => "required",
            "lname" => "required",
            "email" => "required|email|unique:clients,email",
            "phone" => "required|unique:clients,phone",
            "password" => "required|min:6|max:20"
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store client data and OTP in session temporarily
        $clientData = $req->only(['fname', 'lname', 'email', 'phone', 'password']);
        $clientData['password'] = bcrypt($clientData['password']);
        if ($req->hasFile("image")) {
            $file = $req->file("image");
            $filename = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("images"), $filename);
            $clientData['image'] = $filename;
        }
        Session::put('client_registration_data', $clientData);
        Session::put('client_registration_otp', $otp);

        // Send OTP email
        Mail::to($clientData['email'])->send(new OtpMail($otp));

        // Redirect to OTP verification page
        return redirect()->route('otp.verify.form')->with('success', 'OTP sent to your email. Please verify.');
    }

    // Show OTP verification form
    public function showOtpForm()
    {
        return view('otp_verification');
    }

    // Verify OTP (unified for client and driver)
    public function verifyOtp(Request $req)
    {
        $req->validate([
            'otp' => 'required|digits:6',
        ]);

        $clientOtp = Session::get('client_registration_otp');
        $clientData = Session::get('client_registration_data');

        $driverOtp = Session::get('driver_registration_otp');
        $driverData = Session::get('driver_registration_data');

        if ($clientOtp && $clientData) {
            if ($req->otp == $clientOtp) {
                // Save client data permanently
                $client = new Client();
                $client->fname = $clientData['fname'];
                $client->lname = $clientData['lname'];
                $client->email = $clientData['email'];
                $client->phone = $clientData['phone'];
                $client->password = $clientData['password'];
                $client->image = $clientData['image'] ?? null;
                $client->save();

                // Clear session data
                Session::forget('client_registration_data');
                Session::forget('client_registration_otp');

                return redirect()->route('home')->with('success', 'Registration successful. You can now login.');
            } else {
                return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
            }
        } elseif ($driverOtp && $driverData) {
            if ($req->otp == $driverOtp) {
                // Save driver to database
                $driver = new Driver();
                $driver->fname = $driverData['fname'];
                $driver->lname = $driverData['lname'];
                $driver->email = $driverData['email'];
                $driver->phone = $driverData['phone'];
                $driver->password = $driverData['password'];
                $driver->vehicle_type = $driverData['vehicle_type'];
                $driver->plate_number = $driverData['plate_number'];
                $driver->driver_license = $driverData['driver_license'];
                $driver->price_model = $driverData['price_model'];
                $driver->work_area = $driverData['work_area'];
                $driver->image = $driverData['image'] ?? null;
                $driver->approved = false;
                $driver->save();

                // Clear session data
                Session::forget('driver_registration_data');
                Session::forget('driver_registration_otp');

                return redirect()->route('driver.login')->with('success', 'Registration successful! Please wait for approval.');
            } else {
                return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
            }
        } else {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }
    }

    public function storeDriver(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            "fname" => "required|string|max:255",
            "lname" => "required|string|max:255",
            "email" => "required|email|unique:drivers,email",
            "phone" => "required|string|unique:drivers,phone",
            "password" => "required|min:8|confirmed",
            "vehicle_type" => "required|string|in:motorcycle,car,van,truck",
            "plate_number" => "required|string|unique:drivers,plate_number",
            "driver_license" => "required|string|unique:drivers,driver_license",
            "price_model" => "required|string|in:fixed,per_km",
            "work_area" => "required|array",
            "work_area.*" => "string",
            "image" => "nullable|image|mimes:jpg,png,jpeg|max:2048",
            "terms" => "required|accepted",
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store driver data and OTP in session temporarily
        $driverData = $validatedData;
        $driverData['password'] = bcrypt($validatedData['password']);
        $driverData['work_area'] = implode(',', $validatedData['work_area']);
        $driverData['otp'] = $otp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/drivers'), $imageName);
            $driverData['image'] = 'images/drivers/' . $imageName;
        }

        Session::put('driver_registration_data', $driverData);
        Session::put('driver_registration_otp', $otp);
 
        // Send OTP email
        Mail::to($driverData['email'])->send(new OtpMail($otp));

        // Redirect to OTP verification page
        return redirect()->route('otp.verify.form')->with('success', 'OTP sent to your email. Please verify.');
    }

    // Verify OTP for driver registration
    public function verifyDriverOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = Session::get('driver_registration_otp');
        $driverData = Session::get('driver_registration_data');

        if (!$sessionOtp || !$driverData) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        if ($request->otp == $sessionOtp) {
            // Save driver to database
            $driver = new Driver();
            $driver->fname = $driverData['fname'];
            $driver->lname = $driverData['lname'];
            $driver->email = $driverData['email'];
            $driver->phone = $driverData['phone'];
            $driver->password = $driverData['password'];
            $driver->vehicle_type = $driverData['vehicle_type'];
            $driver->plate_number = $driverData['plate_number'];
            $driver->driver_license = $driverData['driver_license'];
            $driver->price_model = $driverData['price_model'];
            $driver->work_area = $driverData['work_area'];
            $driver->image = $driverData['image'] ?? null;
            $driver->approved = false;
            $driver->save();

            Session::forget('driver_registration_data');
            Session::forget('driver_registration_otp');

            return redirect()->route('driver.login')->with('success', 'Registration successful! Please wait for approval.');
        } else {
            return back()->withErrors('Invalid OTP. Please try again.');
        }
    }
}
