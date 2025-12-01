<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');
include '../includes/header.php';
<?php include('../includes/navbar.php'); ?>
$result = mysqli_query($conn, "SELECT * FROM employees ORDER BY name");
?>
<h3>Employees</h3>
<a href="add_employee.php" class="btn btn-success mb-3">Add Employee</a>
<table class="table table-striped">
  <thead><tr><th>Name</th><th>Position</th><th>Contact</th><th>Salary</th><th>Actions</th></tr></thead>
  <tbody>
    <?php while($r = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['position'])?></td>
        <td><?=htmlspecialchars($r['contact'])?></td>
        <td><?=number_format($r['salary'],2)?></td>
        <td>
          <a href="edit_employee.php?id=<?=$r['employee_id']?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="../process/delete_employee.php?id=<?=$r['employee_id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../includes/footer.php'; ?>