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
    
    /* Custom badge styles for priorities */
    .badge-high { background-color: #dc3545; }
    .badge-medium { background-color: #fd7e14; }
    .badge-low { background-color: #28a745; }
    
    /* Custom badge styles for statuses */
    .badge-new { background-color: #0d6efd; }
    .badge-open { background-color: #6610f2; }
    .badge-in-progress { background-color: #6f42c1; }
    .badge-resolved { background-color: #20c997; }
    .badge-closed { background-color: #6c757d; }
    
    /* Hover effects for rows */
    .table tbody tr:hover {
      background-color: rgba(44, 62, 80, 0.1);
      transition: background-color 0.2s ease;
    }
    
    /* Card styling */
    .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
    }
    
    /* Pagination styling */
    .pagination {
      justify-content: center;
      margin-top: 1.5rem;
    }
    
    .page-item.active .page-link {
      background-color: #2c3e50;
      border-color: #2c3e50;
    }
    
    .page-link {
      color: #2c3e50;
    }
    
    .page-link:hover {
      color: #1a252f;
    }
    
    /* Per page dropdown */
    .per-page-dropdown {
      display: inline-block;
      margin-right: 15px;
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

          <div class="card mb-3">
            <div class="card-body">
              <form method="GET" action="{{ route('dashboard.tickets') }}" class="row g-2">
                  <div class="col-md-3">
                      <label for="priority" class="form-label">Priority</label>
                      <select name="priority" id="priority" class="form-select">
                        <option value="">All Priorities</option>
                        <option value="high" {{ (isset($priority) && $priority === 'high') ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ (isset($priority) && $priority === 'medium') ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ (isset($priority) && $priority === 'low') ? 'selected' : '' }}>Low</option>
                        <option value="closed" {{ (isset($priority) && $priority === 'closed') ? 'selected' : '' }}>Closed</option>
                        <option value="urgent" {{ (isset($priority) && $priority === 'urgent') ? 'selected' : '' }}>Urgent</option>
                        <option value="critical" {{ (isset($priority) && $priority === 'critical') ? 'selected' : '' }}>Critical</option>
                      </select>
                  </div>
                  <div class="col-md-4">
                      <label for="start_date" class="form-label">Date Filter</label>
                      <input type="date" name="start_date" id="start_date" class="form-control" 
                            value="{{ $start_date ?? '' }}">
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary me-2">Filter</button>
                      <a href="{{ route('dashboard.tickets') }}" class="btn btn-secondary">Clear</a>
                  </div>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
              <h5 class="mb-0">All Tickets</h5>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>ID Customer</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Expense Amount</th>
                    <th>Expense Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($tickets as $ticket)
                    <tr>
                      <td>{{ $ticket['ticketId'] }}</td>
                      <td>CL-{{ $ticket['customerId']}} </td>
                      <td>{{ $ticket['subject'] }}</td>
                      <td>
                        @php
                          $statusClass = '';
                          switch(strtolower($ticket['status'])) {
                            case 'new': 
                              $statusClass = 'badge-new'; 
                              break;
                            case 'open': 
                              $statusClass = 'badge-open'; 
                              break;
                            case 'in progress': 
                              $statusClass = 'badge-in-progress'; 
                              break;
                            case 'resolved': 
                              $statusClass = 'badge-resolved'; 
                              break;
                            case 'closed': 
                              $statusClass = 'badge-closed'; 
                              break;
                            default: 
                              $statusClass = 'bg-secondary';
                          }
                        @endphp
                        <span class="badge rounded-pill {{ $statusClass }}">
                          {{ $ticket['status'] }}
                        </span>
                      </td>
                      <td>
                        @php
                          $priorityClass = '';
                          switch(strtolower($ticket['priority'])) {
                            case 'high': 
                              $priorityClass = 'badge-high'; 
                              break;
                            case 'medium': 
                              $priorityClass = 'badge-medium'; 
                              break;
                            case 'low': 
                              $priorityClass = 'badge-low'; 
                              break;
                            default: 
                              $priorityClass = 'bg-secondary';
                          }
                        @endphp
                        <span class="badge rounded-pill {{ $priorityClass }}">
                          {{ $ticket['priority'] }}
                        </span>
                      </td>
                      <td>{{ $ticket['expense']['amount'] ?? 'N/A' }}</td>
                      <td>{{ $ticket['expense']['expenseDate'] ?? 'N/A' }}</td>
                      <td>
                        <div class="d-flex gap-1">
                          <a href="{{ route('dashboard.ticket.update', $ticket['ticketId']) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <form action="{{ route('dashboard.ticket.delete', $ticket['ticketId']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                          <p class="mt-2 mb-0">No tickets found</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              
              <!-- Pagination links -->
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets
                </div>
                <div>
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>