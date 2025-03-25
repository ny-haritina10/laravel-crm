<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\DashboardService;
use App\Services\TicketService;
use App\Services\LeadService;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $ticketService;
    protected $leadService;
    protected $perPage = 5; // Default items per page

    public function __construct(
        DashboardService $dashboardService,
        TicketService $ticketService,
        LeadService $leadService
    ) {
        $this->dashboardService = $dashboardService;
        $this->ticketService = $ticketService;
        $this->leadService = $leadService;
    }

    public function index()
    {
        return route('dashboard.manager');
    }

    public function managerDashboard(Request $request)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $counts = $this->dashboardService->getDashboardCounts($token);            
            $totalExpenses = $this->dashboardService->getTotalExpense($token);
            $totalBudget = $this->dashboardService->getTotalBudget($token);

            return view('main.dashboard-manager', [
                'totalTickets' => $counts['totalTickets'] ?? 0,
                'totalLeads' => $counts['totalLeads'] ?? 0,
                'totalExpenses' => $totalExpenses['expenses'] ?? 0,
                'totalBudget' => $totalBudget['totalBudget'] ?? 0
            ]);
        } catch (\Exception $e) {
            return view('main.dashboard-manager', [
                'totalTickets' => 0,
                'totalLeads' => 0,
                'totalExpenses' => 0,
                'totalBudget' => 0,
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function budgetsList(Request $request)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $budgets = $this->dashboardService->getAllBudgets($token);
            
            // Create pagination from array
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', $this->perPage);
            $offset = ($page - 1) * $perPage;
            
            $items = array_slice($budgets, $offset, $perPage, true);
            $paginator = new LengthAwarePaginator(
                $items,
                count($budgets),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('budgets.budgets-list', ['budgets' => $paginator]);
        } catch (\Exception $e) {
            return view('budgets.budgets-list', [
                'budgets' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function expensesList(Request $request)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $expenses = $this->dashboardService->getAllExpenses($token);
            
            // Create pagination from array
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', $this->perPage);
            $offset = ($page - 1) * $perPage;
            
            $items = array_slice($expenses, $offset, $perPage, true);
            $paginator = new LengthAwarePaginator(
                $items,
                count($expenses),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('expenses.expenses-list', ['expenses' => $paginator]);
        } catch (\Exception $e) {
            return view('expenses.expenses-list', [
                'expenses' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function ticketsList(Request $request)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $tickets = $this->ticketService->getAllTickets($token);
            
            // Create pagination from array
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', $this->perPage);
            $offset = ($page - 1) * $perPage;
            
            $items = array_slice($tickets, $offset, $perPage, true);
            $paginator = new LengthAwarePaginator(
                $items, 
                count($tickets), 
                $perPage, 
                $page, 
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('tickets.tickets-list', ['tickets' => $paginator]);
        } catch (\Exception $e) {
            return view('tickets.tickets-list', [
                'tickets' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function leadsList(Request $request)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $leads = $this->leadService->getAllLeads($token);
            
            // Create pagination from array
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', $this->perPage);
            $offset = ($page - 1) * $perPage;
            
            $items = array_slice($leads, $offset, $perPage, true);
            $paginator = new LengthAwarePaginator(
                $items, 
                count($leads), 
                $perPage, 
                $page, 
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('leads.leads-list', ['leads' => $paginator]);
        } catch (\Exception $e) {
            return view('leads.leads-list', [
                'leads' => [],
                'error' => 'Error connecting to CRM: ' . $e->getMessage()
            ]);
        }
    }

    public function showTicketUpdateForm(Request $request, $ticketId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $ticket = $this->ticketService->getTicketById($token, $ticketId);
            if ($ticket) {
                return view('tickets.ticket-update', ['ticket' => $ticket]);
            }
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Ticket not found']);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateTicketExpense(Request $request, $ticketId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        // Validate request data
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'expenseDate' => 'required|date'
        ]);

        try {
            $result = $this->ticketService->processTicketExpenseUpdate(
                $token,
                $ticketId,
                (float)$request->input('amount', 0),
                $request->expenseDate
            );

            if (isset($result['exceedsBudget']) && $result['exceedsBudget']) {
                // Store pending data in session
                foreach ($result as $key => $value) {
                    $request->session()->flash($key, $value);
                }
                return redirect()->route('dashboard.ticket.confirm', ['ticketId' => $ticketId]);
            }

            if (isset($result['success'])) {
                return redirect()->route('dashboard.tickets')->with('success', $result['message']);
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showTicketConfirm(Request $request, $ticketId)
    {
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
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        // Get values from form submission
        $pendingExpenseDate = $request->input('expenseDate');
        $pendingAmount = $request->input('amount');

        if (!$pendingExpenseDate || !$pendingAmount) {
            return redirect()->route('dashboard.tickets')
                ->withErrors(['error' => 'Expense data is missing']);
        }

        try {
            $result = $this->ticketService->confirmTicketExpense(
                $token,
                $ticketId,
                (float)$pendingAmount,
                $pendingExpenseDate
            );

            if ($result['success']) {
                return redirect()->route('dashboard.tickets')->with('success', 'Expense saved despite exceeding the budget');
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function deleteTicketExpense(Request $request, $ticketId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $result = $this->ticketService->deleteTicketExpense($token, $ticketId);
            if ($result['success']) {
                return redirect()->route('dashboard.tickets')->with('success', 'Ticket expense deleted successfully');
            }

            return redirect()->route('dashboard.tickets')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.tickets')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showLeadUpdateForm(Request $request, $leadId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $lead = $this->leadService->getLeadById($token, $leadId);
            if ($lead) {
                return view('leads.lead-update', ['lead' => $lead]);
            }
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Lead not found']);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateLeadExpense(Request $request, $leadId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        // Validate request data
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'expenseDate' => 'required|date'
        ]);

        try {
            $result = $this->leadService->processLeadExpenseUpdate(
                $token,
                $leadId,
                (float)$request->input('amount', 0),
                $request->expenseDate
            );

            if (isset($result['exceedsBudget']) && $result['exceedsBudget']) {
                // Store pending data in session
                foreach ($result as $key => $value) {
                    $request->session()->flash($key, $value);
                }
                return redirect()->route('dashboard.lead.confirm', ['leadId' => $leadId]);
            }

            if (isset($result['success'])) {
                return redirect()->route('dashboard.leads')->with('success', $result['message']);
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showLeadConfirm(Request $request, $leadId)
    {
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
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        // Get values from form submission
        $pendingExpenseDate = $request->input('expenseDate');
        $pendingAmount = $request->input('amount');

        if (!$pendingExpenseDate || !$pendingAmount) {
            return redirect()->route('dashboard.leads')
                ->withErrors(['error' => 'Expense data is missing']);
        }

        try {
            $result = $this->leadService->confirmLeadExpense(
                $token,
                $leadId,
                (float)$pendingAmount,
                $pendingExpenseDate
            );

            if ($result['success']) {
                return redirect()->route('dashboard.leads')->with('success', 'Expense saved despite exceeding the budget');
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function deleteLeadExpense(Request $request, $leadId)
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        try {
            $result = $this->leadService->deleteLeadExpense($token, $leadId);
            if ($result['success']) {
                return redirect()->route('dashboard.leads')->with('success', 'Lead expense deleted successfully');
            }

            return redirect()->route('dashboard.leads')->withErrors(['error' => $result['message']]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.leads')->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function analytics()
    {
        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['auth' => 'Please login first']);
        }

        return view('main.dashboard-analytics');
    }
}