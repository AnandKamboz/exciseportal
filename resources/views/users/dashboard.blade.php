<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #212529;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 6px;
        }
        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }
        .sidebar h4 {
            color: #fff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h4>My App</h4>
            <a href="#">🏠 Home</a>
            <a href="#">👥 Users</a>
            <a href="#">📊 Reports</a>
            <a href="#">⚙️ Settings</a>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 p-4">
            <h2 class="mb-4">Welcome to Dashboard</h2>

            <div class="card shadow-sm p-3 mb-3">
                <h5>Hello, {{ Auth::user()->name ?? 'Guest' }}</h5>
                <p>This is your dashboard where you can manage users, reports, and settings.</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 mb-3">
                        <h6>Total Users</h6>
                        <p class="fs-4 text-primary">120</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 mb-3">
                        <h6>Reports</h6>
                        <p class="fs-4 text-success">45</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 mb-3">
                        <h6>Notifications</h6>
                        <p class="fs-4 text-danger">8</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!-- Hello -->
