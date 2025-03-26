<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Duplicate Data - CRM App</title>
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
                        <i class="bi bi-file-earmark-arrow-up-fill me-2"></i>
                    </h1>
                    <div class="user-info">
                        <span class="me-2">Welcome, {{ session('username', 'Manager') }}</span>
                        <i class="bi bi-person-circle"></i>
                    </div>
                </div>
            </header>
            <main class="p-4">
                <div class="container-fluid">
                    <div class="form-container">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Upload JSON File</h5>
                                <form action="{{ route('import.submit') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="file" class="form-label">JSON File</label>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                               name="file" id="file" accept=".json" required>
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">Import</button>
                                        <a href="{{ route('dashboard.manager') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
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