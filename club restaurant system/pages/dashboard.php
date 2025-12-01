<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
include '../includes/header.php';
// today's sales total
$res = mysqli_query($conn, "SELECT IFNULL(SUM(total_amount),0) AS total FROM sales WHERE DATE(sale_date)=CURDATE()");
$row = mysqli_fetch_assoc($res);
$today = $row['total'] ?? 0;

$employeesCount = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM employees"))[0];
$lowStockCount = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM stock WHERE quantity < reorder_level"))[0];
?>
<h3>Dashboard</h3>
<div class="row">
  <div class="col-md-4"><div class="card p-3 mb-3"><h5>Today's Sales</h5><p class="h4">KSH <?=number_format($today,2)?></p></div></div>
  <div class="col-md-4"><div class="card p-3 mb-3"><h5>Employees</h5><p class="h4"><?=$employeesCount?></p></div></div>
  <div class="col-md-4"><div class="card p-3 mb-3"><h5>Low Stock</h5><p class="h4"><?=$lowStockCount?></p></div></div>
</div>
<li><a href="pages/employees.php">Manage Employees</a></li>
       

<?php include '../includes/footer.php'; ?>