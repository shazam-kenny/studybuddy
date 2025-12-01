<?php include('../includes/navbar.php'); ?>
<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
include '../includes/header.php';
?>
<h3>Add Employee</h3>
<form method="post" action="../process/add_employee.php" onsubmit="return validateEmployeeForm();">
  <div class="mb-3"><label>Name</label><input name="name" id="name" class="form-control" required></div>
  <div class="mb-3"><label>Position</label><input name="position" id="position" class="form-control"></div>
  <div class="mb-3"><label>Contact</label><input name="contact" id="contact" class="form-control"></div>
  <div class="mb-3"><label>Salary</label><input name="salary" id="salary" class="form-control" type="number" step="0.01"></div>
  <button class="btn btn-primary">Save</button>
  <a href="employees.php" class="btn btn-secondary">Back</a>
</form>
<?php include '../includes/footer.php'; ?>