<?php include "includes/navbar.php"; ?>
<?php
// includes/auth.php
session_start();

require_once __DIR__ . '/db.php';

function isLoggedIn() {
    return !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /cst/public/login.php');
        exit;
    }
}

function login($usernameOrEmail, $password) {
    $pdo = getDb();
    $stmt = $pdo->prepare("SELECT id, password_hash, role_id, username FROM users WHERE username = :u OR email = :u LIMIT 1");
    $stmt->execute([':u' => $usernameOrEmail]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        setcookie(session_name(), '', time()-42000);
    }
    session_destroy();
}