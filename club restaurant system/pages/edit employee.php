<?php include('../includes/navbar.php'); ?>
<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$res = mysqli_query($conn, "SELECT * FROM employees WHERE employee_id = $id");
$row = mysqli_fetch_assoc($res);
include '../includes/header.php';
?>
<h3>Edit Employee</h3>
<form method="post" action="../process/update_employee.php">
  <input type="hidden" name="id" value="<?=$row['employee_id']?>">
  <div class="mb-3"><label>Name</label><input name="name" value="<?=htmlspecialchars($row['name'])?>" class="form-control" required></div>
  <div class="mb-3"><label>Position</label><input name="position" value="<?=htmlspecialchars($row['position'])?>" class="form-control"></div>
  <div class="mb-3"><label>Contact</label><input name="contact" value="<?=htmlspecialchars($row['contact'])?>" class="form-control"></div>
  <div class="mb-3"><label>Salary</label><input name="salary" value="<?=htmlspecialchars($row['salary'])?>" class="form-control" type="number" step="0.01"></div>
  <button class="btn btn-primary">Update</button>
  <a href="employees.php" class="btn btn-secondary">Back</a>
</form>
<?php include '../includes/footer.php'; ?>