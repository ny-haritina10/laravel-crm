<div class="sidebar d-flex flex-column flex-shrink-0">
  <div class="brand-container p-3">
    <a href="{{ route('dashboard.manager')}}" class="brand-link d-flex align-items-center text-decoration-none">
      <div class="brand-icon me-2">
        <i class="bi bi-layers-fill"></i>
      </div>
      <span class="brand-text">Manager - CRM</span>
    </a>
  </div>
  
  <hr class="sidebar-divider">
  
  <div class="nav-section px-3">
    <div class="nav-section-header">
      <span>Sidebar - Nav</span>
    </div>
    <ul class="nav-menu nav flex-column">
      <li class="nav-item mb-2">
        <a href="{{ route('dashboard.manager')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="bi bi-house-door-fill me-2"></i> 
          <span>Tableau de bord</span> 
        </a>
      </li>

      <li class="nav-item mb-2">
        <a href="{{ route('alerte-rate.index') }}" class="nav-link {{ request()->routeIs('alerte-rate.index') ? 'active' : '' }}">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> 
          <span>Configuration Seuil</span> 
        </a>
      </li>

      <li class="nav-item mb-2">
        <a href="{{ route('import.index') }}" class="nav-link {{ request()->routeIs('alerte-rate.index') ? 'active' : '' }}">
          <i class="bi bi-files me-2"></i> 
          <span>Import duplication</span> 
        </a>
      </li>
    </ul>
  </div>
  
  <div class="sidebar-footer">
    <hr class="sidebar-divider">
    <div class="logout-container px-3 pb-3">
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="logout-button">
          <i class="bi bi-box-arrow-right me-2"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success" role="alert">
      <div class="alert-icon">
        <i class="bi bi-check-circle-fill"></i>
      </div>
      <div class="alert-content">
        {{ session('success') }}
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  
  @if (session('error'))
    <div class="alert alert-danger" role="alert">
      <div class="alert-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <div class="alert-content">
        {{ session('error') }}
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
</div>

<style>
  :root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --sidebar-bg: #1e2a38;
    --sidebar-hover: #2c3e50;
    --sidebar-active: #3a5170;
    --sidebar-text: #e2e8f0;
    --sidebar-muted: #94a3b8;
    --badge-bg: #4cc9f0;
  }

  .sidebar {
    background-color: var(--sidebar-bg);
    background-image: linear-gradient(180deg, #1e2a38 0%, #2c3e50 100%);
    min-height: 100vh;
    width: 280px;
    box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
    overflow-y: auto;
    transition: all 0.3s ease;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    z-index: 100;
    display: flex;
    flex-direction: column;
  }

  .brand-container {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
  }

  .brand-link {
    color: white;
    transition: all 0.3s ease;
  }

  .brand-link:hover {
    opacity: 0.9;
  }

  .brand-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .brand-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    letter-spacing: 0.5px;
  }

  .sidebar-divider {
    margin: 0.5rem 0;
    border-color: rgba(255, 255, 255, 0.1);
    opacity: 0.5;
  }

  .nav-section {
    flex: 1;
    overflow-y: auto;
  }

  .nav-section-header {
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    color: var(--sidebar-muted);
    margin-bottom: 0.75rem;
    padding-left: 0.5rem;
  }

  .nav-menu {
    list-style: none;
    padding-left: 0;
  }

  .nav-link {
    display: flex;
    align-items: center;
    color: var(--sidebar-text);
    padding: 0.75rem 1rem;
    border-radius: 10px;
    transition: all 0.2s ease;
    font-weight: 500;
  }

  .nav-link:hover {
    background-color: var(--sidebar-hover);
    color: white;
    transform: translateX(5px);
  }

  .nav-link.active {
    background-color: var(--sidebar-active);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    color: white;
    font-weight: 600;
  }

  .nav-link i {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
  }

  .badge {
    background-color: var(--badge-bg);
    color: var(--sidebar-bg);
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.35rem 0.65rem;
  }

  .sidebar-footer {
    margin-top: auto;
    width: 100%;
  }

  .logout-container {
    width: 100%;
  }

  .logout-button {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.75rem 1rem;
    border: none;
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--sidebar-text);
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .logout-button:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
  }

  .logout-button i {
    font-size: 1.2rem;
  }

  .alert {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    margin: 1rem;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    position: fixed;
    bottom: 1rem;
    left: 1rem;
    max-width: 350px;
    z-index: 1000;
    animation: slideIn 0.3s ease-out forwards;
  }

  .alert-success {
    background-color: #d1fae5;
    color: #065f46;
  }

  .alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
  }

  .alert-icon {
    flex-shrink: 0;
    margin-right: 0.75rem;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .alert-content {
    flex-grow: 1;
  }

  .btn-close {
    color: currentColor;
    opacity: 0.5;
    transition: opacity 0.2s;
    padding: 0.25rem;
    background: transparent;
    border: none;
    cursor: pointer;
  }

  .btn-close:hover {
    opacity: 1;
  }

  @keyframes slideIn {
    from {
      transform: translateY(20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* Add margin to main content to accommodate fixed sidebar */
  main {
    margin-left: 280px;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .sidebar {
      width: 80px;
    }
    
    .brand-text,
    .nav-link span,
    .logout-button span,
    .nav-section-header {
      display: none;
    }
    
    .nav-link {
      justify-content: center;
      padding: 0.75rem;
    }
    
    .nav-link i {
      margin-right: 0;
      font-size: 1.4rem;
    }
    
    .badge {
      position: absolute;
      top: 5px;
      right: 5px;
      padding: 0.25rem 0.4rem;
      font-size: 0.65rem;
    }
    
    .brand-container {
      display: flex;
      justify-content: center;
    }
    
    .brand-icon {
      margin-right: 0;
    }
    
    .logout-button {
      justify-content: center;
    }
    
    .alert {
      left: 90px;
      max-width: 250px;
    }
    
    main {
      margin-left: 80px;
    }
  }
</style>