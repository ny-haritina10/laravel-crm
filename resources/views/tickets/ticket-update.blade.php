<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Ticket Expense - New App</title>
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
            <i class="bi bi-ticket-detailed me-2"></i>
          </h1>
          <div class="user-info">
            <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
            <i class="bi bi-person-circle"></i>
          </div>
        </div>
      </header>
      <main class="p-4">
        <div class="container-fluid">
          @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
          @endif

          <div class="form-container">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Update Ticket #{{ $ticket['ticketId'] }} - {{ $ticket['subject'] }}</h5>
                <form action="{{ route('dashboard.ticket.update.submit', $ticket['ticketId']) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                    <label for="amount" class="form-label">Expense Amount</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $ticket['expense']['amount'] ?? '' }}" required>
                  </div>
                  <div class="mb-3">
                    <label for="expenseDate" class="form-label">Expense Date</label>
                    <input type="date" class="form-control" id="expenseDate" name="expenseDate" value="{{ $ticket['expense']['expenseDate'] ?? '' }}" required>
                  </div>
                  <button type="submit" class="btn btn-primary">Update Expense</button>
                  <a href="{{ route('dashboard.tickets') }}" class="btn btn-secondary">Cancel</a>
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