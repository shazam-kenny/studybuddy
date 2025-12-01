<?php
require_once 'config.php';
$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "Admin already exists.";
    exit;
}
mysqli_stmt_close($stmt);

$ins = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
mysqli_stmt_bind_param($ins, 'ss', $username, $hash);
mysqli_stmt_execute($ins);
echo "Admin created: admin / admin123 — change password after login.";