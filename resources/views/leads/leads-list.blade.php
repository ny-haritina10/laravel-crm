<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leads List - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .header { background-color: #2c3e50; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.5rem; }
    .user-info { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    main { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 70px); }
    .table { border-radius: 15px; overflow: hidden; }
    .table thead { background-color: #2c3e50; color: white; }
    
    /* Custom badge styles for lead statuses */
    .badge-new { background-color: #0d6efd; }
    .badge-contacted { background-color: #6610f2; }
    .badge-qualified { background-color: #6f42c1; }
    .badge-prospect { background-color: #fd7e14; }
    .badge-converted { background-color: #198754; }
    .badge-closed { background-color: #6c757d; }
    .badge-rejected { background-color: #dc3545; }
    
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
            Leads List
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
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
              <h5 class="mb-0">All Leads</h5>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Expense Amount</th>
                    <th>Expense Date</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($leads as $lead)
                    <tr>
                      <td>{{ $lead['leadId'] }}</td>
                      <td>{{ $lead['name'] }}</td>
                      <td>
                        @php
                          $statusClass = '';
                          switch(strtolower($lead['status'])) {
                            case 'new': 
                              $statusClass = 'badge-new'; 
                              break;
                            case 'contacted': 
                              $statusClass = 'badge-contacted'; 
                              break;
                            case 'qualified': 
                              $statusClass = 'badge-qualified'; 
                              break;
                            case 'prospect': 
                              $statusClass = 'badge-prospect'; 
                              break;
                            case 'converted': 
                              $statusClass = 'badge-converted'; 
                              break;
                            case 'closed': 
                              $statusClass = 'badge-closed'; 
                              break;
                            case 'rejected': 
                              $statusClass = 'badge-rejected'; 
                              break;
                            default: 
                              $statusClass = 'bg-secondary';
                          }
                        @endphp
                        <span class="badge rounded-pill {{ $statusClass }}">
                          {{ $lead['status'] }}
                        </span>
                      </td>
                      <td>{{ $lead['phone'] }}</td>
                      <td>{{ $lead['expense']['amount'] ?? 'N/A' }}</td>
                      <td>{{ $lead['expense']['expenseDate'] ?? 'N/A' }}</td>
                      <td>{{ $lead['createdAt'] }}</td>
                      <td>
                        <div class="d-flex gap-1">
                          <a href="{{ route('dashboard.lead.update', $lead['leadId']) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <form action="{{ route('dashboard.lead.delete', $lead['leadId']) }}" method="POST">
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
                          <p class="mt-2 mb-0">No leads found</p>
                        </div>
                      </td>
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