<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - New App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .login-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .card {
      min-width: 400px;
      border: none;
      border-radius: 15px;
    }

    .card-title {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #2c3e50;
      font-weight: 600;
    }

    .form-control {
      border-radius: 0 0.375rem 0.375rem 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .btn-primary {
      background-color: #2c3e50;
      border-color: #2c3e50;
      padding: 12px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #34495e;
      border-color: #34495e;
    }

    .input-group-text {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card shadow-lg">
      <div class="card-body p-5">
        <h2 class="card-title text-center mb-4">
          <i class="bi bi-lock-fill me-2"></i>New-App | Manager Login
        </h2>

        @if ($errors->has('auth')) <!-- Check for 'auth' error -->
          <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first('auth') }}
          </div>
        @endif

        <form action="{{ route('login') }}" method="post" id="loginForm">
          @csrf
          <div class="mb-4">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
              </span>
              <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Username or email"
                value="{{ old('username') }}"
                required
              >
            </div>
            @error('username') <!-- Changed from 'email' to 'username' -->
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-key-fill"></i>
              </span>
              <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Password"
                required
              >
            </div>
            @error('password')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button
            type="submit"
            class="btn btn-primary w-100 mt-3"
            id="submitButton"
          >
            <span id="spinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
            <span id="buttonText">Login</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function () {
      const submitButton = document.getElementById('submitButton');
      const spinner = document.getElementById('spinner');
      const buttonText = document.getElementById('buttonText');

      submitButton.disabled = true;
      spinner.classList.remove('d-none');
      buttonText.textContent = 'Logging in...';
    });
  </script>
</body>
</html>