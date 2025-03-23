<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadService
{
    protected $crmApiUrl;
    protected $alertThresholdPercentage = 80;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    /**
     * Get all leads from the CRM API
     */
    public function getAllLeads(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/expenses/leads');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch leads: ' . $response->body());
    }

    /**
     * Get a specific lead by ID
     */
    public function getLeadById(string $token, int $leadId)
    {
        $leads = $this->getAllLeads($token);
        return collect($leads)->firstWhere('leadId', (int)$leadId);
    }

    /**
     * Process lead expense update and check for budget constraints
     */
    public function processLeadExpenseUpdate(string $token, int $leadId, float $amount, string $expenseDate): array
    {
        // Fetch lead data to get budget info
        $lead = $this->getLeadById($token, $leadId);
        if (!$lead) {
            return [
                'success' => false,
                'message' => 'Lead not found'
            ];
        }

        $currentUsedBudget = (float)($lead['currentUsedBudget'] ?? 0);
        $totalAllocatedBudget = (float)($lead['totalAllocatedBudget'] ?? 0);
        $alertRatePercentage = (float)($lead['alertRatePercentage'] ?? $this->alertThresholdPercentage);
        $currentExpenseAmount = (float)($lead['expense']['amount'] ?? 0);
        $adjustedUsedBudget = $currentUsedBudget - $currentExpenseAmount + $amount;
        $remainingBudget = $totalAllocatedBudget - $currentUsedBudget;

        Log::info("Lead #$leadId Budget Check", [
            'newAmount' => $amount,
            'currentUsedBudget' => $currentUsedBudget,
            'totalAllocatedBudget' => $totalAllocatedBudget,
            'currentExpenseAmount' => $currentExpenseAmount,
            'adjustedUsedBudget' => $adjustedUsedBudget,
            'remainingBudget' => $remainingBudget,
            'exceedsBudget' => $adjustedUsedBudget > $totalAllocatedBudget,
            'expenseDate' => $expenseDate
        ]);

        // Check if expense exceeds budget
        if ($adjustedUsedBudget > $totalAllocatedBudget && $totalAllocatedBudget > 0) {
            return [
                'pendingLeadId' => $leadId,
                'pendingAmount' => $amount,
                'pendingExpenseDate' => $expenseDate,
                'exceedsBudget' => true,
                'remainingBudget' => $remainingBudget,
                'totalBudget' => $totalAllocatedBudget
            ];
        }

        // Update the expense
        return $this->updateLeadExpense($token, $leadId, $amount, $expenseDate, $adjustedUsedBudget, $totalAllocatedBudget, $alertRatePercentage);
    }

    /**
     * Update lead expense
     */
    private function updateLeadExpense(
        string $token,
        int $leadId,
        float $amount,
        string $expenseDate,
        float $adjustedUsedBudget,
        float $totalAllocatedBudget,
        float $alertRatePercentage
    ): array {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->crmApiUrl . '/api/expenses/lead/' . $leadId, [
            'amount' => $amount,
            'expenseDate' => $expenseDate
        ]);

        if ($response->successful()) {
            $message = 'Lead expense updated successfully';
            
            // Check if budget threshold is reached
            if ($totalAllocatedBudget > 0) {
                $alertThreshold = $totalAllocatedBudget * ($alertRatePercentage / 100);
                if ($adjustedUsedBudget >= $alertThreshold) {
                    $usedPercentage = ($adjustedUsedBudget / $totalAllocatedBudget) * 100;
                    $message .= ". Alert: You have reached " . number_format($usedPercentage, 1) . "% of the total budget.";
                }
            }
            
            return [
                'success' => true,
                'message' => $message
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to update lead expense: ' . $response->body()
        ];
    }

    /**
     * Confirm lead expense that exceeds budget
     */
    public function confirmLeadExpense(string $token, int $leadId, float $amount, string $expenseDate): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->crmApiUrl . '/api/expenses/lead/' . $leadId, [
            'amount' => $amount,
            'expenseDate' => $expenseDate
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Expense saved despite exceeding the budget'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to confirm lead expense: ' . $response->body()
        ];
    }

    /**
     * Delete lead expense
     */
    public function deleteLeadExpense(string $token, int $leadId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete($this->crmApiUrl . '/api/expenses/lead/' . $leadId);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Lead expense deleted successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete lead expense: ' . $response->body()
        ];
    }
}