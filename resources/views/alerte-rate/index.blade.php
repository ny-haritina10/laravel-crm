<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte Rates - CRM App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .header { background-color: #2c3e50; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.5rem; }
        .user-info { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        main { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: calc(100vh - 70px); }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('layouts.sidebar')

        <div class="flex-grow-1">
            <header class="header p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="bi bi-bell-fill me-2"></i>
                        Alerte Rates
                    </h1>
                    <div class="user-info">
                        <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
                        <i class="bi bi-person-circle"></i>
                    </div>
                </div>
            </header>
            <main class="p-4">
                <div class="container-fluid">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Alerte Rate List</h5>
                            @if (empty($alerteRates))
                                <p>No Alerte Rates found.</p>
                            @else
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Percentage</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alerteRates as $alerteRate)
                                            <tr>
                                                <td>{{ $alerteRate['alerteRateId'] }}</td>
                                                <td>{{ $alerteRate['percentage'] }}%</td>
                                                <td>{{ \Carbon\Carbon::parse($alerteRate['alerteRateDate'])->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    <a href="{{ route('alerte-rate.edit', $alerteRate['alerteRateId']) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil-fill"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>