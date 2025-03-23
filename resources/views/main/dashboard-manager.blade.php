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

    .card {
      border-radius: 15px;
      border: none;
    }

    .card-title {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #2c3e50;
      margin-bottom: 0.5rem;
    }

    .card-text {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #34495e;
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
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>