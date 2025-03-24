<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Lead Expense - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .header { 
      background-color: #2c3e50; 
      color: white; 
      border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
    }
    h1 { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      font-size: 1.5rem; 
    }
    .user-info { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    }
    main { 
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
      min-height: calc(100vh - 70px);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .form-container { 
      max-width: 550px; 
      width: 100%;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .card-header {
      background-color: #2c3e50;
      color: white;
      border-bottom: none;
      padding: 1.25rem;
    }
    .budget-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border-radius: 10px;
      background-color: rgba(0, 0, 0, 0.03);
    }
    .budget-label {
      display: flex;
      align-items: center;
    }
    .budget-icon {
      margin-right: 0.5rem;
      font-size: 1.25rem;
    }
    .expense-alert {
      display: flex;
      align-items: center;
      border-left: 4px solid #ff9800;
      background-color: rgba(255, 152, 0, 0.1);
      padding: 1.25rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }
    .alert-icon {
      font-size: 2rem;
      color: #ff9800;
      margin-right: 1rem;
    }
    .btn-action {
      padding: 0.6rem 1.5rem;
      border-radius: 50px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-right: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }
    .btn-confirm {
      background-color: #2c3e50;
      border-color: #2c3e50;
    }
    .btn-confirm:hover {
      background-color: #1a2530;
      border-color: #1a2530;
    }
    .btn-cancel {
      background-color: #f8f9fa;
      border-color: #f8f9fa;
      color: #495057;
    }
    .btn-cancel:hover {
      background-color: #e2e6ea;
      border-color: #dae0e5;
      color: #212529;
    }
    .expense-amount {
      font-size: 1.5rem;
      font-weight: 700;
      color: #ff9800;
    }
    .card-body {
      padding: 2rem;
    }
    .lead-id {
      font-weight: 600;
      color: #495057;
    }
    .action-buttons {
      margin-top: 2rem;
      display: flex;
      justify-content: flex-end;
    }
    .progress {
      height: 0.5rem;
      margin-top: 0.5rem;
      margin-bottom: 1.5rem;
      border-radius: 50px;
      background-color: rgba(0, 0, 0, 0.05);
    }
    .progress-bar {
      background-color: #ff9800;
      border-radius: 50px;
    }
    .budget-percentage {
      font-size: 0.85rem;
      color: #6c757d;
      text-align: right;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    @include('layouts.sidebar')

    <div class="flex-grow-1">
      <header class="header p-3">
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="mb-0">
            <i class="bi bi-person-lines-fill me-2"></i>
          </h1>
          <div class="user-info">
            <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
            <i class="bi bi-person-circle"></i>
          </div>
        </div>
      </header>
      <main class="p-4">
        <div class="form-container">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <h5 class="mb-0">Budget Confirmation Required</h5>
            </div>
            <div class="card-body">
              <h5 class="mb-4">
                <span class="lead-id">Lead #{{ $leadId }}</span> - Expense Approval
              </h5>
              
              <div class="expense-alert">
                <div class="alert-icon">
                  <i class="bi bi-lightbulb"></i>
                </div>
                <div>
                  <h6 class="fw-bold">Marketing Budget Alert</h6>
                  <p class="mb-0">This expense will exceed your allocated marketing budget for leads. Additional approval may be required.</p>
                </div>
              </div>
              
              <div class="mb-4">
                <h6 class="text-muted">New Expense Amount</h6>
                <div class="expense-amount">
                  <i class="bi bi-currency-dollar"></i> {{ $amount }}
                </div>
              </div>
              
              <div class="budget-info">
                <div class="budget-label">
                  <i class="bi bi-cash-stack budget-icon text-success"></i>
                  <span>Total Budget</span>
                </div>
                <div class="fw-bold">${{ $totalBudget }}</div>
              </div>
              
              <div class="budget-info">
                <div class="budget-label">
                  <i class="bi bi-wallet2 budget-icon text-primary"></i>
                  <span>Remaining Budget</span>
                </div>
                <div class="fw-bold">${{ $remainingBudget }}</div>
              </div>
              
              @php
                $percentageUsed = (($totalBudget - $remainingBudget) / $totalBudget) * 100;
                $newPercentageUsed = (($totalBudget - $remainingBudget + $amount) / $totalBudget) * 100;
              @endphp
              
              <h6 class="mt-4 mb-0">Current Budget Usage</h6>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $percentageUsed }}%" aria-valuenow="{{ $percentageUsed }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="budget-percentage">{{ number_format($percentageUsed, 1) }}% used</div>
              
              <h6 class="mt-3 mb-0">After This Expense</h6>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $newPercentageUsed }}%" aria-valuenow="{{ $newPercentageUsed }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="budget-percentage">{{ number_format($newPercentageUsed, 1) }}% used</div>
              
              <div class="budget-info mt-3">
                <div class="budget-label">
                  <i class="bi bi-calendar-date budget-icon text-info"></i>
                  <span>Expense Date</span>
                </div>
                <div class="fw-bold">{{ $expenseDate }}</div>
              </div>
              
              <form action="{{ route('dashboard.lead.confirm.submit', $leadId) }}" method="POST">
                @csrf
                <input type="hidden" name="expenseDate" value="{{ $expenseDate }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <div class="action-buttons">
                  <a href="{{ route('dashboard.leads') }}" class="btn btn-cancel btn-action">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                  </a>
                  <button type="submit" class="btn btn-confirm btn-action btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Confirm Expense
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>