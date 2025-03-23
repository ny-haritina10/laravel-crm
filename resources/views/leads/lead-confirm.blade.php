<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Lead Expense - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .header { background-color: #2c3e50; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.5rem; }
    .user-info { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    main { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 70px); }
    .form-container { max-width: 500px; margin: 0 auto; }
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
            Confirm Lead Expense
          </h1>
          <div class="user-info">
            <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
            <i class="bi bi-person-circle"></i>
          </div>
        </div>
      </header>
      <main class="p-4">
        <div class="container-fluid">
          <div class="form-container">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Confirm Expense for Lead #{{ $leadId }}</h5>
                <div class="alert alert-warning">
                  <p>The new expense of ${{ $amount }} will exceed the total budget.</p>
                  <p>Total Budget: ${{ $totalBudget }}</p>
                  <p>Remaining Budget: ${{ $remainingBudget }}</p>
                  <p>Do you want to proceed?</p>
                </div>
                <form action="{{ route('dashboard.lead.confirm.submit', $leadId) }}" method="POST">
                  @csrf
                  <input type="hidden" name="expenseDate" value="{{ $expenseDate }}">
                  <input type="hidden" name="amount" value="{{ $amount }}">
                  <button type="submit" class="btn btn-primary">Confirm</button>
                  <a href="{{ route('dashboard.leads') }}" class="btn btn-secondary">Cancel</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>