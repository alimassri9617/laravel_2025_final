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

        return view('admin.dashboard');
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