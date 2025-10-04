<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'User Dashboard')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: #fff;
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }
    .main-container {
      flex: 1;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      min-height: 100vh;
      background: linear-gradient(180deg, #ff9983, #FF8A73);
      transition: width 0.3s;
      color: #fff;
      width: 230px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    .sidebar.collapsed {
      width: 70px;
    }
    .sidebar .logo {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      padding-bottom: 15px;
    }
    .sidebar .logo img {
      max-width: 100%;
      height: 60px;
      object-fit: contain;
    }
    .sidebar .nav-link {
      color: #fff;
      font-weight: 500;
      margin: 5px 0;
      border-radius: 10px;
      padding: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.3s;
    }
    .sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.25);
    }
    .sidebar.collapsed .nav-link span {
      display: none;
    }
    .sidebar.collapsed .nav-link {
      justify-content: center;
    }

    /* Topbar */
    .topbar {
      background: linear-gradient(135deg, #FF8A73, #FFEEDC);
      padding: 12px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      color: #333;
      font-weight: bold;
    }
    .topbar h4 {
      color: #FF3200;
      font-weight: 700;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .card h2 {
      color: #FF3200;
      font-weight: bold;
    }
    .card h5 {
      color: #555;
    }

    /* Table */
    thead {
      background: #FF8A73;
      color: #fff;
    }
    tbody tr:hover {
      background: rgba(255, 138, 115, 0.1);
    }

    /* Button */
    .btn-custom {
      background: #FF1C00;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background: #cc1600;
      color: #fff;
    }

    /* Footer */
    footer {
      background: linear-gradient(135deg, #FF8A73, #FFEEDC);
      color: #fff;
      padding: 15px;
      text-align: center;
      font-size: 14px;
      margin-top: auto;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="main-container">
    <div id="sidebar" class="sidebar p-3 d-flex flex-column">
        <div class="logo">
        <img src="{{ asset('assets/images/banner/haryana-logo.png') }}" alt="Logo">
        </div>

        <ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('inspector.dashboard'') }}">
        <i class="bi bi-house"></i> <span>Dashboard</span>
        </a>
    </li>

   <li class="nav-item">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-link text-white border-0 bg-transparent w-100 text-start">
        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
      </button>
    </form>
  </li>
    </ul>
</div>

  <!-- Main Content -->
  <div class="flex-grow-1 d-flex flex-column">

    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center">
      <button class="btn btn-light" id="toggleSidebar">
        <i class="bi bi-list"></i>
      </button>
      <h4 class="m-0">@yield('page_title', 'User Dashboard')</h4>
      <div>
        <i class="bi bi-bell me-3 fs-5"></i>
        <i class="bi bi-person-circle fs-5"></i>
      </div>
    </div>

    <!-- Dynamic Content -->
    <div class="container-fluid mt-4 flex-grow-1">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
      &copy; 2025 All Rights Reserved.
    </footer>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById("toggleSidebar").addEventListener("click", function() {
    document.getElementById("sidebar").classList.toggle("collapsed");
  });
</script>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#complaintsTable').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            order: [[4, 'desc']], 
            columnDefs: [
                { orderable: false, targets: 3 }
            ]
        });
    });
</script>

@stack('scripts')
</body>
</html>
