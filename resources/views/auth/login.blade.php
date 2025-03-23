<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - New App</title>
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
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      padding: 2rem 1rem;
      position: relative;
      overflow: hidden;
    }
    
    /* Decorative background elements */
    body::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      opacity: 0.05;
      top: -250px;
      left: -250px;
    }
    
    body::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--info-color), var(--success-color));
      opacity: 0.05;
      bottom: -150px;
      right: -150px;
    }
    
    .login-container {
      width: 100%;
      max-width: 450px;
      position: relative;
      z-index: 10;
    }
    
    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      background-color: white;
      animation: fadeIn 0.6s ease-out;
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      text-align: center;
      padding: 2rem 0;
      border-bottom: none;
      height: 220px;
    }
    
    .app-logo {
      margin-bottom: 1rem;
    }
    
    .logo-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }
    
    .logo-icon {
      font-size: 2.5rem;
      color: white;
    }
    
    .app-name {
      font-size: 1.8rem;
      font-weight: 700;
      margin: 0;
    }
    
    .login-subtitle {
      font-size: 1rem;
      opacity: 0.8;
      margin-top: 0.5rem;
    }
    
    .card-body {
      padding: 2.5rem;
    }
    
    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    
    .form-control {
      height: 50px;
      border-radius: 10px;
      border: 2px solid #e2e8f0;
      padding-left: 1rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }
    
    .input-group-text {
      background-color: #f8fafc;
      border: 2px solid #e2e8f0;
      border-right: none;
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
      color: #64748b;
    }
    
    .input-group .form-control {
      border-left: none;
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }
    
    .form-control.is-invalid,
    .was-validated .form-control:invalid {
      border-color: var(--warning-color);
      background-image: none;
    }
    
    .text-danger {
      color: var(--warning-color) !important;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 10px;
      height: 50px;
      font-weight: 600;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 12px rgba(67, 97, 238, 0.25);
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    }
    
    .btn-primary:active {
      transform: translateY(0);
    }
    
    .alert {
      border-radius: 10px;
      border: none;
      animation: shake 0.5s ease-in-out;
    }
    
    .alert-danger {
      background-color: #fee2e2;
      color: #991b1b;
    }
    
    /* Remember me and forgot password */
    .form-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .forgot-password {
      color: var(--primary-color);
      font-size: 0.9rem;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .forgot-password:hover {
      color: var(--secondary-color);
      text-decoration: underline;
    }
    
    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
      .card-body {
        padding: 1.5rem;
      }
      
      .app-name {
        font-size: 1.5rem;
      }
      
      .logo-circle {
        width: 60px;
        height: 60px;
      }
      
      .logo-icon {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card">
      <div class="card-header">
        <div class="app-logo">
          <div class="logo-circle">
            <i class="bi bi-layers-fill logo-icon"></i>
          </div>
        </div>
        <h1 class="app-name">New-App</h1>
        <p class="login-subtitle">Manager Portal</p>
      </div>
      <div class="card-body">
        @if ($errors->has('auth'))
          <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ $errors->first('auth') }}</div>
          </div>
        @endif

        <form action="{{ route('login') }}" method="post" id="loginForm">
          @csrf
          <div class="mb-4">
            <label for="username" class="form-label">Username or Email</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
              </span>
              <input
                type="text"
                id="username"
                name="username"
                class="form-control @error('username') is-invalid @enderror"
                placeholder="Enter your username or email"
                value="{{ old('username') }}"
                required
                autocomplete="username"
                autofocus
              >
            </div>
            @error('username')
              <div class="text-danger mt-2">
                <i class="bi bi-info-circle-fill me-1"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-shield-lock-fill"></i>
              </span>
              <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Enter your password"
                required
                autocomplete="current-password"
              >
            </div>
            @error('password')
              <div class="text-danger mt-2">
                <i class="bi bi-info-circle-fill me-1"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <button
            type="submit"
            class="btn btn-primary w-100"
            id="submitButton"
          >
            <span id="spinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
            <span id="buttonText">Sign In</span>
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
      buttonText.textContent = 'Signing in...';
    });
  </script>
</body>
</html>