<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: ../pages/login.php');

$id = intval($_POST['id'] ?? 0);
$name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
$position = mysqli_real_escape_string($conn, $_POST['position'] ?? '');
$contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
$salary = floatval($_POST['salary'] ?? 0);

$stmt = mysqli_prepare($conn, "UPDATE employees SET name=?, position=?, contact=?, salary=? WHERE employee_id=?");
mysqli_stmt_bind_param($stmt, 'sssdi', $name, $position, $contact, $salary, $id);
mysqli_stmt_execute($stmt);
header('Location: ../pages/employees.php');
exit;