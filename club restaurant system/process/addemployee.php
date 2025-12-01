<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: ../pages/login.php');

$name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
$position = mysqli_real_escape_string($conn, $_POST['position'] ?? '');
$contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
$salary = floatval($_POST['salary'] ?? 0);

$stmt = mysqli_prepare($conn, "INSERT INTO employees (name, position, contact, salary) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sssd', $name, $position, $contact, $salary);
mysqli_stmt_execute($stmt);
header('Location: ../pages/employees.php');
exit;