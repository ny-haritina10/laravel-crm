<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tickets List - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .header { background-color: #2c3e50; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.5rem; }
    .user-info { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    main { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 70px); }
    .table { border-radius: 15px; overflow: hidden; }
    .table thead { background-color: #2c3e50; color: white; }
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
            Tickets List
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

          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Expense Amount</th>
                    <th>Expense Date</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($tickets as $ticket)
                    <tr>
                      <td>{{ $ticket['ticketId'] }}</td>
                      <td>{{ $ticket['subject'] }}</td>
                      <td>{{ $ticket['status'] }}</td>
                      <td>{{ $ticket['priority'] }}</td>
                      <td>{{ $ticket['expense']['amount'] ?? 'N/A' }}</td>
                      <td>{{ $ticket['expense']['expenseDate'] ?? 'N/A' }}</td>
                      <td>{{ $ticket['createdAt'] }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center">No tickets found</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>