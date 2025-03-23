<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Analytics - New App</title>
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

    .chart-card {
      border-radius: 15px;
      border: none;
      transition: all 0.3s ease;
      overflow: hidden;
      margin-bottom: 1.5rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      background-color: white;
    }

    .chart-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .chart-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-color);
      padding: 1rem 1.5rem;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
    }

    .chart-icon {
      margin-right: 10px;
      color: var(--primary-color);
    }

    .chart-container {
      padding: 1.5rem;
      position: relative;
      height: 350px;
    }

    /* Animation for chart cards */
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

    .chart-card {
      animation: fadeInUp 0.5s ease-out forwards;
    }

    .chart-card:nth-child(2) {
      animation-delay: 0.2s;
    }

    .chart-card:nth-child(3) {
      animation-delay: 0.4s;
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
            <i class="bi bi-bar-chart-line-fill me-2"></i>
            Analytics Dashboard
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

          <div class="row g-4">
            <!-- Ticket Status Chart (Pie Chart) -->
            <div class="col-md-12 col-lg-6">
              <div class="chart-card">
                <div class="chart-title">
                  <i class="bi bi-pie-chart-fill chart-icon"></i>
                  Ticket Status Distribution
                </div>
                <div class="chart-container">
                  <canvas id="ticketStatusChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Monthly Expenses Chart (Bar Chart) -->
            <div class="col-md-12 col-lg-6">
              <div class="chart-card">
                <div class="chart-title">
                  <i class="bi bi-bar-chart-fill chart-icon"></i>
                  Monthly Expenses
                </div>
                <div class="chart-container">
                  <canvas id="monthlyExpensesChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Budget Evolution Chart (Line Chart) -->
            <div class="col-md-12">
              <div class="chart-card">
                <div class="chart-title">
                  <i class="bi bi-graph-up chart-icon"></i>
                  Budget Evolution
                </div>
                <div class="chart-container">
                  <canvas id="budgetEvolutionChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Get token from wherever it's stored (example: localStorage)
    const token = localStorage.getItem('auth_token') || '{{ session('token') }}';
    console.log('Token being used:', token);

    // Base fetch configuration with headers
    const fetchConfig = {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'include',
      mode: 'cors'
    };

    // Function to format status text for display
    function formatStatus(status) {
      return status.split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    }

    // Function to generate random colors
    function generateColors(count) {
      const colors = [
        '#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0',
        '#480ca8', '#4895ef', '#560bad', '#b5179e', '#3f37c9'
      ];

      while (colors.length < count) {
        const r = Math.floor(Math.random() * 255);
        const g = Math.floor(Math.random() * 255);
        const b = Math.floor(Math.random() * 255);
        colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
      }

      return colors.slice(0, count);
    }

    // Format date for display (YYYY-MM to Mon YYYY)
    function formatDateLabel(dateStr) {
      const [year, month] = dateStr.split('-');
      const date = new Date(year, month - 1);
      return date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Helper function to handle fetch with better error reporting
      async function fetchData(url) {
        try {
          const response = await fetch(url, fetchConfig);
          if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
          }
          return await response.json();
        } catch (error) {
          console.error(`Fetch failed for ${url}:`, error);
          throw error;
        }
      }

      // TICKET STATUS PIE CHART
      fetchData('http://localhost:8080/api/dashboard/ticket-status')
        .then(data => {
          const labels = data.map(item => formatStatus(item.status));
          const values = data.map(item => item.count);
          const colors = generateColors(data.length);

          new Chart(document.getElementById('ticketStatusChart'), {
            type: 'pie',
            data: {
              labels: labels,
              datasets: [{ data: values, backgroundColor: colors, borderWidth: 1 }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { position: 'right', labels: { padding: 20, boxWidth: 15, font: { size: 12 } } },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      const label = context.label || '';
                      const value = context.raw || 0;
                      const total = context.dataset.data.reduce((acc, cur) => acc + cur, 0);
                      const percentage = Math.round((value / total) * 100);
                      return `${label}: ${value} (${percentage}%)`;
                    }
                  }
                }
              }
            }
          });
        })
        .catch(error => console.error('Error fetching ticket status data:', error));

      // MONTHLY EXPENSES BAR CHART
      fetchData('http://localhost:8080/api/dashboard/monthly-expenses')
        .then(data => {
          const labels = data.map(item => formatDateLabel(item.month));
          const values = data.map(item => item.totalAmount);

          new Chart(document.getElementById('monthlyExpensesChart'), {
            type: 'bar',
            data: {
              labels: labels,
              datasets: [{
                label: 'Total Expenses',
                data: values,
                backgroundColor: '#4361ee',
                borderColor: '#3f37c9',
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: { callback: value => '$' + value.toLocaleString() }
                }
              },
              plugins: {
                tooltip: { callbacks: { label: context => 'Expenses: $' + context.raw.toLocaleString() } }
              }
            }
          });
        })
        .catch(error => console.error('Error fetching monthly expenses data:', error));

      // BUDGET EVOLUTION LINE CHART
      Promise.all([
        fetchData('http://localhost:8080/api/dashboard/budget-evolution'),
        fetchData('http://localhost:8080/api/dashboard/monthly-expenses')
      ])
        .then(([budgetData, expensesData]) => {
          const expensesByMonth = {};
          expensesData.forEach(item => expensesByMonth[item.month] = item.totalAmount);

          const labels = budgetData.map(item => formatDateLabel(item.date));
          const budgetValues = budgetData.map(item => item.totalBudget);
          const expenseValues = budgetData.map(item => expensesByMonth[item.date] || 0);
          const remainingValues = budgetData.map((item, index) => Math.max(0, item.totalBudget - (expensesByMonth[item.date] || 0)));

          new Chart(document.getElementById('budgetEvolutionChart'), {
            type: 'line',
            data: {
              labels: labels,
              datasets: [
                {
                  label: 'Total Budget',
                  data: budgetValues,
                  borderColor: '#4cc9f0',
                  backgroundColor: 'rgba(76, 201, 240, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.3
                },
                {
                  label: 'Expenses',
                  data: expenseValues,
                  borderColor: '#f72585',
                  backgroundColor: 'rgba(247, 37, 133, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.3
                },
                {
                  label: 'Remaining Budget',
                  data: remainingValues,
                  borderColor: '#4361ee',
                  backgroundColor: 'rgba(67, 97, 238, 0.1)',
                  borderWidth: 2,
                  fill: true,
                  tension: 0.3
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: { beginAtZero: true, ticks: { callback: value => '$' + value.toLocaleString() } }
              },
              plugins: {
                tooltip: { callbacks: { label: context => context.dataset.label + ': $' + context.raw.toLocaleString() } }
              }
            }
          });
        })
        .catch(error => console.error('Error fetching budget evolution data:', error));
    });
  </script>
</body>
</html>