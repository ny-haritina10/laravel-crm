<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('main.dashboard');
    }

    public function managerDashboard()
    {
        return view('main.dashboard-manager');
    }
}