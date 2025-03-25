<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    protected $crmApiUrl;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    /**
     * Get dashboard counts from the CRM API
     */
    public function getDashboardCounts(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/dashboard/counts');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch dashboard data: ' . $response->body());
    }

    public function getTotalExpense(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/dashboard/total_expenses');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch dashboard data: ' . $response->body());
    }

    public function getTotalBudget(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/dashboard/total_budget');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch dashboard data: ' . $response->body());
    }

    public function getAllBudgets(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/dashboard/budgets');

        if ($response->successful()) {
            return $response->json()['budgets'] ?? [];
        }

        throw new \Exception('Failed to fetch budgets: ' . $response->body());
    }

    public function getAllExpenses(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/dashboard/expenses');

        if ($response->successful()) {
            return $response->json()['expenses'] ?? [];
        }

        throw new \Exception('Failed to fetch expenses: ' . $response->body());
    }
}