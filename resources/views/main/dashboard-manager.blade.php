<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard - New App</title>
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
    }

    .stat-card {
      border-radius: 15px;
      border: none;
      transition: transform 0.2s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 2rem;
      color: #fff;
      background: linear-gradient(45deg, #2c3e50, #3498db);
      padding: 15px;
      border-radius: 10px;
    }

    .stat-number {
      font-size: 1.8rem;
      font-weight: bold;
      color: #2c3e50;
    }

    .stat-label {
      color: #7f8c8d;
      font-size: 1.1rem;
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
            <i class="bi bi-speedometer2 me-2"></i>
            Manager Dashboard
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

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Welcome to the Manager Dashboard</h5>
                  <p class="card-text">
                    Hello, {{ session('username', 'Manager') }}! This is your control center. Use the sidebar to navigate between different sections.
                  </p>
                </div>
              </div>
            </div>
          </div> <br>
          
          <div class="row g-4 mb-4">
            <div class="col-md-12 col-lg-6">
              <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                  <div class="stat-icon me-3">
                    <i class="bi bi-ticket-detailed"></i>
                  </div>
                  <div>
                    <div class="stat-number">{{ $totalTickets }}</div>
                    <div class="stat-label">Total Tickets</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-6">
              <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                  <div class="stat-icon me-3">
                    <i class="bi bi-person-lines-fill"></i>
                  </div>
                  <div>
                    <div class="stat-number">{{ $totalLeads }}</div>
                    <div class="stat-label">Total Leads</div>
                  </div>
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