<?php include('../includes/navbar.php'); ?>
<?php
session_start();
if (isset($_SESSION['user'])) header('Location: dashboard.php');
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4">
          <h4 class="card-title">Login</h4>
          <?php if ($error): ?><div class="alert alert-danger">Invalid credentials</div><?php endif;?>
          <form method="post" action="../process/login.php" onsubmit="return validateLogin();">
            <div class="mb-3">
              <label>Username</label>
              <input name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Login</button>
          </form>
          <small class="text-muted mt-2 d-block">Default admin: admin / admin123</small>
        </div>
      </div>
    </div>
  </div>
<script src="/club_restaurant_system/assets/js/validation.js"></script>
</body>
</html>