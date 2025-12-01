<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Club & Restaurant Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/club_restaurant_system/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/club_restaurant_system/pages/dashboard.php">Club & Restaurant</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/employees.php">Employees</a></li>
        <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/stock.php">Stock</a></li>
        <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/sales.php">Sales</a></li>
        <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/reports.php">Reports</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if ($user): ?>
          <li class="nav-item"><span class="nav-link">Hello, <?=htmlspecialchars($user['username'])?></span></li>
          <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/club_restaurant_system/pages/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">