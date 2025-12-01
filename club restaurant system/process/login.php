<?php
session_start();
require_once '../config.php';
if (!isset($_POST['username'], $_POST['password'])) header('Location: ../pages/login.php?error=1');

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = mysqli_prepare($conn, "SELECT user_id, username, password, role FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $user_id, $db_user, $db_pass, $role);
if (mysqli_stmt_fetch($stmt)) {
    if (password_verify($password, $db_pass)) {
        $_SESSION['user'] = ['id'=>$user_id,'username'=>$db_user,'role'=>$role];
        mysqli_stmt_close($stmt);
        header('Location: ../pages/dashboard.php');
        exit;
    }
}
mysqli_stmt_close($stmt);
header('Location: ../pages/login.php?error=1');
exit;