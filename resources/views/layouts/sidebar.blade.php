<div class="sidebar d-flex flex-column flex-shrink-0 p-3">
  <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <i class="bi bi-bootstrap-fill me-2"></i>
    <span class="fs-4">New-App</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="#" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill me-2"></i>
        Dashboard
      </a>
    </li>
  </ul>
  <hr>
  <div class="logout">
    <form action="{{ route('logout') }}" method="post" class="d-flex align-items-center">
      @csrf
      <i class="bi bi-box-arrow-left me-2 text-white"></i>
      <input class="nav-link text-white p-0 border-0 bg-transparent" type="submit" value="Logout">
    </form>
  </div>

  @if (session('success'))
    <div class="alert alert-success mt-3" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger mt-3" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      {{ session('error') }}
    </div>
  @endif
</div>

<style>
  .logout .nav-link {
    cursor: pointer; /* Makes it look clickable */
  }

  .logout .nav-link:hover {
    background-color: #34495e; /* Matches your existing hover style */
  }
  .sidebar {
    background-color: #2c3e50;
    min-height: 100vh;
    width: 250px;
  }

  .nav-link {
    transition: all 0.3s ease;
  }

  .nav-link:hover {
    background-color: #34495e;
  }

  .active {
    background-color: #34495e;
  }

  hr {
    border-color: rgba(255, 255, 255, 0.2);
  }

  .fs-4 {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .dropdown-menu {
    background-color: #fff;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .dropdown-item {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #2c3e50;
  }

  .dropdown-item:hover {
    background-color: #f5f7fa;
  }

  .alert {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: fixed;
    bottom: 20px;
    left: 260px;
    z-index: 1000;
    max-width: 400px;
  }
</style>