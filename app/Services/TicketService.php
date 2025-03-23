<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketService
{
    protected $crmApiUrl;
    protected $alertThresholdPercentage = 80;

    public function __construct()
    {
        $this->crmApiUrl = env('CRM_API_URL', 'http://localhost:8080');
    }

    /**
     * Get all tickets from the CRM API
     */
    public function getAllTickets(string $token): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($this->crmApiUrl . '/api/expenses/tickets');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch tickets: ' . $response->body());
    }

    /**
     * Get a specific ticket by ID
     */
    public function getTicketById(string $token, int $ticketId)
    {
        $tickets = $this->getAllTickets($token);
        return collect($tickets)->firstWhere('ticketId', (int)$ticketId);
    }

    /**
     * Process ticket expense update and check for budget constraints
     */
    public function processTicketExpenseUpdate(string $token, int $ticketId, float $amount, string $expenseDate): array
    {
        // Fetch ticket data to get budget info
        $ticket = $this->getTicketById($token, $ticketId);
        if (!$ticket) {
            return [
                'success' => false,
                'message' => 'Ticket not found'
            ];
        }

        $currentUsedBudget = (float)($ticket['currentUsedBudget'] ?? 0);
        $totalAllocatedBudget = (float)($ticket['totalAllocatedBudget'] ?? 0);
        $alertRatePercentage = (float)($ticket['alertRatePercentage'] ?? $this->alertThresholdPercentage);
        $currentExpenseAmount = (float)($ticket['expense']['amount'] ?? 0);
        $adjustedUsedBudget = $currentUsedBudget - $currentExpenseAmount + $amount;
        $remainingBudget = $totalAllocatedBudget - $currentUsedBudget;

        // Check if expense exceeds budget
        if ($adjustedUsedBudget > $totalAllocatedBudget && $totalAllocatedBudget > 0) {
            return [
                'pendingTicketId' => $ticketId,
                'pendingAmount' => $amount,
                'pendingExpenseDate' => $expenseDate,
                'exceedsBudget' => true,
                'remainingBudget' => $remainingBudget,
                'totalBudget' => $totalAllocatedBudget
            ];
        }

        // Update the expense
        return $this->updateTicketExpense($token, $ticketId, $amount, $expenseDate, $adjustedUsedBudget, $totalAllocatedBudget, $alertRatePercentage);
    }

    /**
     * Update ticket expense
     */
    private function updateTicketExpense(
        string $token,
        int $ticketId,
        float $amount,
        string $expenseDate,
        float $adjustedUsedBudget,
        float $totalAllocatedBudget,
        float $alertRatePercentage
    ): array {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId, [
            'amount' => $amount,
            'expenseDate' => $expenseDate
        ]);

        if ($response->successful()) {
            $message = 'Ticket expense updated successfully';
            
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
            'message' => 'Failed to update ticket expense: ' . $response->body()
        ];
    }

    /**
     * Confirm ticket expense that exceeds budget
     */
    public function confirmTicketExpense(string $token, int $ticketId, float $amount, string $expenseDate): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId, [
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
            'message' => 'Failed to confirm ticket expense: ' . $response->body()
        ];
    }

    /**
     * Delete ticket expense
     */
    public function deleteTicketExpense(string $token, int $ticketId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->delete($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Ticket expense deleted successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete ticket expense: ' . $response->body()
        ];
    }
}