<?php
// templates/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Chama Savings Tracker</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/cst/assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1><a href="/cst/public/index.php">Chama Savings Tracker</a></h1>
      <div class="user-info">
        <?php if (!empty($_SESSION['username'])): ?>
          <span>Hello, <?=htmlspecialchars($_SESSION['username'])?></span>
          <a href="/cst/public/logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <nav class="main-nav">
    <div class="container">
      <a href="/cst/public/index.php">Dashboard</a> |
      <a href="/cst/public/members.php">Members</a> |
      <a href="/cst/public/contributions.php">Contributions</a> |
      <a href="/cst/public/reports.php">Reports</a>
    </div>
  </nav>
  <main class="container">