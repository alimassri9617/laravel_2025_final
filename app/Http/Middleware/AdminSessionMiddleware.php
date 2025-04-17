<?php

// app/Http/Middleware/AdminSessionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin') !== true) {
            return redirect()->route('admin.login.form')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
