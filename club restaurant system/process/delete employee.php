<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user'])) header('Location: ../pages/login.php');
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM employees WHERE employee_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}
header('Location: ../pages/employees.php');
exit;