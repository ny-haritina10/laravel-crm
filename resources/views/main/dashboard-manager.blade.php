<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4cc9f0;
      --warning-color: #f72585;
      --info-color: #560bad;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
    }

    .header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 1rem 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-weight: 600;
      font-size: 1.5rem;
      margin: 0;
      letter-spacing: 0.5px;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .avatar:hover {
      background-color: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    main {
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      min-height: calc(100vh - 70px);
      padding: 2rem;
    }

    .stat-card {
      border-radius: 15px;
      border: none;
      transition: all 0.3s ease;
      overflow: hidden;
      margin-bottom: 1.5rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .stat-card:first-child .stat-icon {
      background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
    }

    .stat-card:nth-child(2) .stat-icon {
      background: linear-gradient(45deg, var(--info-color), var(--success-color));
    }

    .stat-icon {
      font-size: 2.2rem;
      color: #fff;
      padding: 18px;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      line-height: 1;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #64748b;
      font-size: 1rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .welcome-card {
      border-radius: 15px;
      background: linear-gradient(to right, var(--light-color), #dfe7f5);
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      margin-bottom: 2rem;
    }

    .welcome-card:hover {
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      color: var(--dark-color);
      font-weight: 600;
    }

    .btn-details {
      background-color: var(--primary-color);
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .btn-details:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Animation for stat cards */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translate3d(0, 30px, 0);
      }
      to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
      }
    }

    .stat-card {
      animation: fadeInUp 0.5s ease-out forwards;
    }

    .stat-card:nth-child(2) {
      animation-delay: 0.2s;
    }
  </style>
</head>

<body>
  <div class="d-flex">
    @include('layouts.sidebar')

    <div class="flex-grow-1">
      <header class="header">
        <div class="d-flex justify-content-between align-items-center">
          <h1>
            <i class="bi bi-layout-text-window-reverse me-2"></i>
            Manager Dashboard
          </h1>
          <div class="user-info">
            <span class="d-none d-md-inline">Welcome, <strong>{{ session('username', 'Manager') }}</strong></span>
            <div class="avatar">
              <i class="bi bi-person-fill"></i>
            </div>
          </div>
        </div>
      </header>
      <main>
        <div class="container-fluid">
          @if(isset($error))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <div>{{ $error }}</div>
            </div>
          @endif

          <div class="row">
            <div class="col-md-12">
              <div class="card welcome-card">
                <div class="card-body p-4">
                  <h5 class="card-title d-flex align-items-center">
                    <i class="bi bi-stars me-2 text-primary"></i>
                    Welcome to your Dashboard
                  </h5>
                  <p class="card-text">
                    Hello, <strong>{{ session('username', 'Manager') }}</strong>! This is your control center for managing all aspects of your business. Use the sidebar navigation to access different sections of the application.
                  </p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row g-4">
            <div class="col-md-12 col-lg-6">
              <div class="stat-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="stat-icon me-4">
                      <i class="bi bi-ticket-perforated-fill"></i>
                    </div>
                    <div>
                      <div class="stat-number">{{ $totalTickets }}</div>
                      <div class="stat-label">Active Tickets</div>
                    </div>
                  </div>
                  <a href="{{ route('dashboard.tickets') }}" class="btn btn-details">
                    <i class="bi bi-arrow-right-circle me-1"></i> Details
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-6">
              <div class="stat-card">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center">
                    <div class="stat-icon me-4">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                      <div class="stat-number">{{ $totalLeads }}</div>
                      <div class="stat-label">Potential Leads</div>
                    </div>
                  </div>
                  <a href="{{ route('dashboard.leads') }}" class="btn btn-details">
                    <i class="bi bi-arrow-right-circle me-1"></i> Details
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>