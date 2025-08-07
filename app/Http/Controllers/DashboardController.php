<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle dashboard access with authentication checks
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // User is logged in, redirect to admin dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // User is not logged in, redirect to login page
            return redirect()->route('login');
        }
    }
} 