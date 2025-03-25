<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlerteRateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['crm.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manager-only routes
    Route::middleware(['crm.role.manager'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/manager', [DashboardController::class, 'managerDashboard'])->name('dashboard.manager');
        Route::get('/dashboard/tickets', [DashboardController::class, 'ticketsList'])->name('dashboard.tickets');
        Route::get('/dashboard/leads', [DashboardController::class, 'leadsList'])->name('dashboard.leads');
        Route::get('/dashboard/ticket/{ticketId}/update', [DashboardController::class, 'showTicketUpdateForm'])->name('dashboard.ticket.update');
        Route::put('/dashboard/ticket/{ticketId}/update', [DashboardController::class, 'updateTicketExpense'])->name('dashboard.ticket.update.submit');
        Route::delete('/dashboard/ticket/{ticketId}/delete', [DashboardController::class, 'deleteTicketExpense'])->name('dashboard.ticket.delete');
        Route::get('/dashboard/ticket/{ticketId}/confirm', [DashboardController::class, 'showTicketConfirm'])->name('dashboard.ticket.confirm');
        Route::post('/dashboard/ticket/{ticketId}/confirm', [DashboardController::class, 'confirmTicketExpense'])->name('dashboard.ticket.confirm.submit');
        
        Route::get('/dashboard/lead/{leadId}/update', [DashboardController::class, 'showLeadUpdateForm'])->name('dashboard.lead.update');
        Route::put('/dashboard/lead/{leadId}/update', [DashboardController::class, 'updateLeadExpense'])->name('dashboard.lead.update.submit');
        Route::delete('/dashboard/lead/{leadId}/delete', [DashboardController::class, 'deleteLeadExpense'])->name('dashboard.lead.delete');
        Route::get('/dashboard/lead/{leadId}/confirm', [DashboardController::class, 'showLeadConfirm'])->name('dashboard.lead.confirm');
        Route::post('/dashboard/lead/{leadId}/confirm', [DashboardController::class, 'confirmLeadExpense'])->name('dashboard.lead.confirm.submit');

        Route::get('/alerte-rate/{id}/edit', [AlerteRateController::class, 'edit'])->name('alerte-rate.edit');
        Route::put('/alerte-rate/{id}', [AlerteRateController::class, 'update'])->name('alerte-rate.update');
        Route::delete('/alerte-rate/{id}', [AlerteRateController::class, 'destroy'])->name('alerte-rate.destroy');
        Route::get('/alerte-rate', [AlerteRateController::class, 'index'])->name('alerte-rate.index');

        Route::get('/dashboard/budgets', [DashboardController::class, 'budgetsList'])->name('dashboard.budgets');
        Route::get('/dashboard/expenses', [DashboardController::class, 'expensesList'])->name('dashboard.expenses');
    });
});