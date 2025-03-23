<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    public function index()
    {
        return view('main.dashboard');
    }

    public function managerDashboard(Request $request)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/dashboard/counts');

            if ($response->successful()) {
                $counts = $response->json();
                return view('main.dashboard-manager', [
                    'totalTickets' => $counts['totalTickets'] ?? 0,
                    'totalLeads' => $counts['totalLeads'] ?? 0
                ]);
            }

            return view('main.dashboard-manager', [
                'totalTickets' => 0,
                'totalLeads' => 0,
                'error' => 'Failed to fetch dashboard data: ' . $response->body()
            ]);
        } catch (\Exception $e) {
            return view('main.dashboard-manager', [
                'totalTickets' => 0,
                'totalLeads' => 0,
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }
}