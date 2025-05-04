<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Admin credentials (in real app, store in .env)
    private $adminCredentials = [
        'username' => 'admin',
        'password' => 'admin123' // Change this in production
    ];

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        if ($request->username === $this->adminCredentials['username'] && 
            $request->password === $this->adminCredentials['password']) {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function dashboard()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Fetch drivers with their reviews and clients eager loaded
        $drivers = Driver::with(['reviews.client'])->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('drivers'));
    }

    public function reviews(Request $request)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Order by first name and last name instead of non-existent 'name' column
        $drivers = Driver::orderBy('fname')->orderBy('lname')->get();

        $selectedDriverId = $request->query('driver_id');
        $selectedDriver = null;
        $reviews = collect();

        if ($selectedDriverId) {
            $selectedDriver = Driver::with(['reviews.client'])->find($selectedDriverId);
            if ($selectedDriver) {
                $reviews = $selectedDriver->reviews;
            }
        }

        return view('admin.reviews', compact('drivers', 'selectedDriver', 'reviews'));
    }

    public function drivers()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $drivers = Driver::orderBy('created_at', 'desc')->get();
        return view('admin.drivers', compact('drivers'));
    }

    public function approveDriver(Driver $driver)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $driver->update(['approved' => true]);
        return back()->with('success', 'Driver approved successfully!');
    }

    public function deleteDriver(Driver $driver)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $driver->delete();
        return back()->with('success', 'Driver deleted successfully!');
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
