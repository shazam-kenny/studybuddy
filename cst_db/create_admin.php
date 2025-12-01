<?php include "includes/navbar.php"; ?>
<?php
// create_admin.php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/includes/db.php';

$pdo = getDb();

// CHANGE THESE before running
$username = 'admin';
$email = 'admin@example.com';
$password = 'admin123'; // change later
$role_id = 1; // admin role

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id) VALUES (:u,:e,:p,:r)");
try {
    $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$password_hash, ':r'=>$role_id]);
    echo "Admin created. Username: $username | Password: $password\n";
} catch (Exception $e) {
    echo "Error: ".$e->getMessage();
}