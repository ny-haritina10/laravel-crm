<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $crmApiUrl;
    protected $alertThresholdPercentage = 80; 

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

    public function ticketsList(Request $request)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/tickets');

            if ($response->successful()) {
                $tickets = $response->json();
                return view('tickets.tickets-list', ['tickets' => $tickets]);
            }

            return view('tickets.tickets-list', [
                'tickets' => [],
                'error' => 'Failed to fetch tickets: ' . $response->body()
            ]);
        } catch (\Exception $e) {
            return view('tickets.tickets-list', [
                'tickets' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function leadsList(Request $request)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/leads');

            if ($response->successful()) {
                $leads = $response->json();
                return view('leads.leads-list', ['leads' => $leads]);
            }

            return view('leads.leads-list', [
                'leads' => [],
                'error' => 'Failed to fetch leads: ' . $response->body()
            ]);
        } catch (\Exception $e) {
            return view('leads.leads-list', [
                'leads' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function showTicketUpdateForm(Request $request, $ticketId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/tickets');

            if ($response->successful()) {
                $tickets = $response->json();
                $ticket = collect($tickets)->firstWhere('ticketId', (int)$ticketId);
                if ($ticket) {
                    return view('tickets.ticket-update', ['ticket' => $ticket]);
                }
                return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Ticket not found']);
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Failed to fetch ticket data']);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateTicketExpense(Request $request, $ticketId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            // Validate request data
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'expenseDate' => 'required|date'
            ]);

            // Fetch ticket data to get budget info
            $ticketResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/tickets');

            if (!$ticketResponse->successful()) {
                return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Failed to fetch ticket data']);
            }

            $tickets = $ticketResponse->json();
            $ticket = collect($tickets)->firstWhere('ticketId', (int)$ticketId);
            if (!$ticket) {
                return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Ticket not found']);
            }

            $newAmount = (float)$request->input('amount', 0);
            $currentUsedBudget = (float)($ticket['currentUsedBudget'] ?? 0);
            $totalAllocatedBudget = (float)($ticket['totalAllocatedBudget'] ?? 0);
            $alertRatePercentage = (float)($ticket['alertRatePercentage'] ?? 80);
            $currentExpenseAmount = (float)($ticket['expense']['amount'] ?? 0);
            $adjustedUsedBudget = $currentUsedBudget - $currentExpenseAmount + $newAmount;
            $remainingBudget = $totalAllocatedBudget - $currentUsedBudget;

            Log::info("Ticket #$ticketId Budget Check", [
                'newAmount' => $newAmount,
                'currentUsedBudget' => $currentUsedBudget,
                'totalAllocatedBudget' => $totalAllocatedBudget,
                'currentExpenseAmount' => $currentExpenseAmount,
                'adjustedUsedBudget' => $adjustedUsedBudget,
                'remainingBudget' => $remainingBudget,
                'exceedsBudget' => $adjustedUsedBudget > $totalAllocatedBudget,
                'expenseDate' => $request->expenseDate
            ]);

            if ($adjustedUsedBudget > $totalAllocatedBudget && $totalAllocatedBudget > 0) {
                $request->session()->flash('pendingTicketId', $ticketId);
                $request->session()->flash('pendingAmount', $newAmount);
                $request->session()->flash('pendingExpenseDate', $request->expenseDate);
                $request->session()->flash('exceedsBudget', true);
                $request->session()->flash('remainingBudget', $remainingBudget);
                $request->session()->flash('totalBudget', $totalAllocatedBudget);
                Log::info("Redirecting to ticket confirmation for Ticket #$ticketId");
                return redirect()->route('dashboard.ticket.confirm', ['ticketId' => $ticketId]);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->put($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId, [
                'amount' => $newAmount,
                'expenseDate' => $request->expenseDate
            ]);

            if ($response->successful()) {
                $alertThreshold = $totalAllocatedBudget * ($alertRatePercentage / 100);
                if ($adjustedUsedBudget >= $alertThreshold && $totalAllocatedBudget > 0) {
                    $usedPercentage = ($adjustedUsedBudget / $totalAllocatedBudget) * 100;
                    return redirect()->route('dashboard.tickets')->with('success',
                        "Ticket expense updated successfully. Alert: You have reached " . number_format($usedPercentage, 1) . "% of the total budget.");
                }
                return redirect()->route('dashboard.tickets')->with('success', 'Ticket expense updated successfully');
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Failed to update ticket expense: ' . $response->body()]);
        } catch (\Exception $e) {
            Log::error("Exception in updateTicketExpense for Ticket #$ticketId: " . $e->getMessage());
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showTicketConfirm(Request $request, $ticketId)
    {
        Log::info("Showing ticket confirmation page", [
            'ticketId' => $request->session()->get('pendingTicketId'),
            'amount' => $request->session()->get('pendingAmount'),
            'expenseDate' => $request->session()->get('pendingExpenseDate')
        ]);
        return view('tickets.ticket-confirm', [
            'ticketId' => $request->session()->get('pendingTicketId'),
            'amount' => $request->session()->get('pendingAmount'),
            'expenseDate' => $request->session()->get('pendingExpenseDate'),
            'remainingBudget' => $request->session()->get('remainingBudget'),
            'totalBudget' => $request->session()->get('totalBudget')
        ]);
    }

    public function confirmTicketExpense(Request $request, $ticketId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }
    
            // Get the expense date from the form submission
            $pendingExpenseDate = $request->input('expenseDate');
            if (!$pendingExpenseDate) {
                return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Expense date is missing']);
            }
    
            // Get the amount from the form submission
            $pendingAmount = $request->input('amount');
            if (!$pendingAmount) {
                return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Amount is missing']);
            }
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->put($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId, [
                'amount' => $pendingAmount,
                'expenseDate' => $pendingExpenseDate
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard.tickets')->with('success', 'Expense saved despite exceeding the budget');
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Failed to confirm ticket expense: ' . $response->body()]);
        } catch (\Exception $e) {
            Log::error("Exception in confirmTicketExpense for Ticket #$ticketId: " . $e->getMessage());
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function deleteTicketExpense(Request $request, $ticketId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->delete($this->crmApiUrl . '/api/expenses/ticket/' . $ticketId);

            if ($response->successful()) {
                return redirect()->route('dashboard.tickets')->with('success', 'Ticket expense deleted successfully');
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Failed to delete ticket expense: ' . $response->body()]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showLeadUpdateForm(Request $request, $leadId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/leads');

            if ($response->successful()) {
                $leads = $response->json();
                $lead = collect($leads)->firstWhere('leadId', (int)$leadId);
                if ($lead) {
                    return view('leads.lead-update', ['lead' => $lead]);
                }
                return redirect()->route('dashboard.leads')->withErrors(['error' => 'Lead not found']);
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Failed to fetch lead data']);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateLeadExpense(Request $request, $leadId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            // Validate request data
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'expenseDate' => 'required|date'
            ]);

            // Fetch lead data to get budget info
            $leadResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($this->crmApiUrl . '/api/expenses/leads');

            if (!$leadResponse->successful()) {
                return redirect()->route('dashboard.leads')->withErrors(['error' => 'Failed to fetch lead data']);
            }

            $leads = $leadResponse->json();
            $lead = collect($leads)->firstWhere('leadId', (int)$leadId);
            if (!$lead) {
                return redirect()->route('dashboard.leads')->withErrors(['error' => 'Lead not found']);
            }

            $newAmount = (float)$request->input('amount', 0);
            $currentUsedBudget = (float)($lead['currentUsedBudget'] ?? 0);
            $totalAllocatedBudget = (float)($lead['totalAllocatedBudget'] ?? 0);
            $alertRatePercentage = (float)($lead['alertRatePercentage'] ?? 80);
            $currentExpenseAmount = (float)($lead['expense']['amount'] ?? 0);
            $adjustedUsedBudget = $currentUsedBudget - $currentExpenseAmount + $newAmount;
            $remainingBudget = $totalAllocatedBudget - $currentUsedBudget;

            Log::info("Lead #$leadId Budget Check", [
                'newAmount' => $newAmount,
                'currentUsedBudget' => $currentUsedBudget,
                'totalAllocatedBudget' => $totalAllocatedBudget,
                'currentExpenseAmount' => $currentExpenseAmount,
                'adjustedUsedBudget' => $adjustedUsedBudget,
                'remainingBudget' => $remainingBudget,
                'exceedsBudget' => $adjustedUsedBudget > $totalAllocatedBudget,
                'expenseDate' => $request->expenseDate
            ]);

            if ($adjustedUsedBudget > $totalAllocatedBudget && $totalAllocatedBudget > 0) {
                $request->session()->flash('pendingLeadId', $leadId);
                $request->session()->flash('pendingAmount', $newAmount);
                $request->session()->flash('pendingExpenseDate', $request->expenseDate);
                $request->session()->flash('exceedsBudget', true);
                $request->session()->flash('remainingBudget', $remainingBudget);
                $request->session()->flash('totalBudget', $totalAllocatedBudget);
                Log::info("Redirecting to lead confirmation for Lead #$leadId");
                return redirect()->route('dashboard.lead.confirm', ['leadId' => $leadId]);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->put($this->crmApiUrl . '/api/expenses/lead/' . $leadId, [
                'amount' => $newAmount,
                'expenseDate' => $request->expenseDate
            ]);

            if ($response->successful()) {
                $alertThreshold = $totalAllocatedBudget * ($alertRatePercentage / 100);
                if ($adjustedUsedBudget >= $alertThreshold && $totalAllocatedBudget > 0) {
                    $usedPercentage = ($adjustedUsedBudget / $totalAllocatedBudget) * 100;
                    return redirect()->route('dashboard.leads')->with('success',
                        "Lead expense updated successfully. Alert: You have reached " . number_format($usedPercentage, 1) . "% of the total budget.");
                }
                return redirect()->route('dashboard.leads')->with('success', 'Lead expense updated successfully');
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Failed to update lead expense: ' . $response->body()]);
        } catch (\Exception $e) {
            Log::error("Exception in updateLeadExpense for Lead #$leadId: " . $e->getMessage());
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showLeadConfirm(Request $request, $leadId)
    {
        Log::info("Showing lead confirmation page", [
            'leadId' => $request->session()->get('pendingLeadId'),
            'amount' => $request->session()->get('pendingAmount'),
            'expenseDate' => $request->session()->get('pendingExpenseDate')
        ]);
        return view('leads.lead-confirm', [
            'leadId' => $request->session()->get('pendingLeadId'),
            'amount' => $request->session()->get('pendingAmount'),
            'expenseDate' => $request->session()->get('pendingExpenseDate'),
            'remainingBudget' => $request->session()->get('remainingBudget'),
            'totalBudget' => $request->session()->get('totalBudget')
        ]);
    }

    public function confirmLeadExpense(Request $request, $leadId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            // Get the expense date and amount from the form submission
            $pendingExpenseDate = $request->input('expenseDate');
            if (!$pendingExpenseDate) {
                return redirect()->route('dashboard.leads')->withErrors(['error' => 'Expense date is missing']);
            }

            $pendingAmount = $request->input('amount');
            if (!$pendingAmount) {
                return redirect()->route('dashboard.leads')->withErrors(['error' => 'Amount is missing']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->put($this->crmApiUrl . '/api/expenses/lead/' . $leadId, [
                'amount' => $pendingAmount,
                'expenseDate' => $pendingExpenseDate
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard.leads')->with('success', 'Expense saved despite exceeding the budget');
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Failed to confirm lead expense: ' . $response->body()]);
        } catch (\Exception $e) {
            Log::error("Exception in confirmLeadExpense for Lead #$leadId: " . $e->getMessage());
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function deleteLeadExpense(Request $request, $leadId)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->delete($this->crmApiUrl . '/api/expenses/lead/' . $leadId);

            if ($response->successful()) {
                return redirect()->route('dashboard.leads')->with('success', 'Lead expense deleted successfully');
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Failed to delete lead expense: ' . $response->body()]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}