<?php

namespace App\Http\Controllers;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Client;

class Reg extends Controller
{
    public function storeUser(Request $req){
        $req->validate([
            "fname"=>"required",
            "lname"=>"required",
            "email"=>"required|email|unique:clients,email",
            "phone"=>"required|unique:clients,phone",
            "password"=>"required|min:6|max:20",
            "image"=>"required|mimes:jpg,png,jpeg"
        ]);

        $client = new Client();
        $client->fname = $req->fname;
        $client->lname = $req->lname;
        $client->email = $req->email;
        $client->phone = $req->phone;
        $client->password = bcrypt($req->password);
        if($req->hasFile("image")){
            $file = $req->file("image");
            $filename = time()."_".$file->getClientOriginalName();
            $file->move(public_path("images"),$filename);
            $client->image = $filename;
        }
        if($client->save()){
            return redirect()->route("home")->with("success","Registration successful");
        }else{
            return redirect()->back()->with("error","Registration failed");
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
        "vicheltype" => "required|string|in:motorcycle,car,van,truck",
        "platenumber" => "required|string|unique:drivers,platenumber",
        "driverlicense" => "required|string|unique:drivers,driverlicense",
        "pricemodel" => "required|string|in:fixed,per_km",
        "work_area" => "sometimes|string", // Optional as it has default value
        "image" => "required|image|mimes:jpg,png,jpeg|max:2048",
        "terms" => "required|accepted",
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images/drivers'), $imageName);
        $validatedData['image'] = 'images/drivers/' . $imageName;
    }

    // Hash the password
    $validatedData['password'] = bcrypt($validatedData['password']);

    // Set default work_area if not provided
    if (!isset($validatedData['work_area'])) {
        $validatedData['work_area'] = 'baalbek';
    }

    // Create the driver
    try {
        $driver = Driver::create($validatedData);
        
        // You might want to log the driver in here
        // auth()->login($driver); // Uncomment if you have authentication setup
        
        return redirect()->route('home')->with([
            'success' => 'Driver registration successful!',
            'driver' => $driver // Optional: pass driver data to the view
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with([
            'error' => 'Registration failed. Please try again.',
            'error_details' => $e->getMessage() // Optional: for debugging
        ]);
    }
}
}
