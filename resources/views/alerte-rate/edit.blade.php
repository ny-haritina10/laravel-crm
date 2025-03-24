<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Alerte Rate - CRM App</title>
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
      .form-container { 
          max-width: 500px; 
          margin: 0 auto; 
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
                        <i class="bi bi-bell-fill me-2"></i>
                        Update Alerte Rate
                    </h1>
                    <div class="user-info">
                        <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
                        <i class="bi bi-person-circle"></i>
                    </div>
                </div>
            </header>
            <main class="p-4">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="form-container">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alerte Rate #{{ $alerteRate['alerteRateId'] }}</h5>
                                <form action="{{ route('alerte-rate.update', $alerteRate['alerteRateId']) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="percentage" class="form-label">Percentage (%)</label>
                                        <input type="number" step="0.01" class="form-control @error('percentage') is-invalid @enderror" 
                                               id="percentage" name="percentage" 
                                               value="{{ old('percentage', $alerteRate['percentage']) }}" required>
                                        @error('percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="alerteRateDate" class="form-label">Alerte Rate Date</label>
                                        <input type="datetime-local" class="form-control @error('alerte_rate_date') is-invalid @enderror" 
                                               id="alerteRateDate" name="alerte_rate_date" 
                                               value="{{ old('alerte_rate_date', \Carbon\Carbon::parse($alerteRate['alerteRateDate'])->format('Y-m-d\TH:i:s')) }}">
                                        @error('alerte_rate_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">Update Alerte Rate</button>
                                        <a href="{{ route('dashboard.manager') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>

                                <!-- Delete Form -->
                                <form action="{{ route('alerte-rate.destroy', $alerteRate['alerteRateId']) }}" method="POST" 
                                      class="mt-3" onsubmit="return confirm('Are you sure you want to delete this Alerte Rate?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">Delete Alerte Rate</button>
                                </form>
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