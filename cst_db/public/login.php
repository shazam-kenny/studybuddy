<?php include "includes/navbar.php"; ?>
<?php
// public/login.php
require_once __DIR__.'/../includes/auth.php';
if (isLoggedIn()) header('Location: index.php');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u === '' || $p === '') {
        $errors[] = "Enter username/email and password.";
    } else {
        if (login($u, $p)) {
            header('Location: index.php'); exit;
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}
include __DIR__.'/../templates/header.php';
?>
<div class="card">
  <h2>Login</h2>
  <?php foreach ($errors as $e): ?>
    <div style="color:red;"><?=htmlspecialchars($e)?></div>
  <?php endforeach; ?>
  <form method="POST" action="login.php">
    <div class="form-row">
      <label>Username or Email</label>
      <input type="text" name="username" required>
    </div>
    <div class="form-row">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <button type="submit">Login</button>
    </div>
  </form>
</div>
<?php include __DIR__.'/../templates/footer.php'; ?>