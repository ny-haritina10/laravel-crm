<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgets List - New App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .header { background-color: #2c3e50; color: white; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.5rem; }
        .user-info { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        main { background: linear-gradient(135deg, #f57fa 0%, #c3cfe2 100%); min-height: calc(100vh - 70px); }
        .table { border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #2c3e50; color: white; }
        .table tbody tr:hover {
            background-color: rgba(44, 62, 80, 0.1);
            transition: background-color 0.2s ease;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .card:hover { transform: translateY(-5px); }
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }
        .page-item.active .page-link {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .page-link { color: #2c3e50; }
        .page-link:hover { color: #1a252f; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('layouts.sidebar')
        <div class="flex-grow-1">
            <header class="header p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="bi bi-wallet2 me-2"></i>
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
                            <h5 class="mb-0">All Budgets</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Label</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($budgets as $budget)
                                        <tr>
                                            <td>{{ $budget['budgetId'] }}</td>
                                            <td>{{ $budget['customerName'] }}</td>
                                            <td>{{ $budget['label'] }}</td>
                                            <td>${{ number_format($budget['amount'], 2) }}</td>
                                            <td>{{ $budget['transactionDate'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                    <p class="mt-2 mb-0">No budgets found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    Showing {{ $budgets->firstItem() }} to {{ $budgets->lastItem() }} of {{ $budgets->total() }} budgets
                                </div>
                                <div>
                                    {{ $budgets->links('pagination::bootstrap-5') }}
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