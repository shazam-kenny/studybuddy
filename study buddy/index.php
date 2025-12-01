<?php 
session_start(); 
include 'db.php'; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
    } else {
        // THIS IS THE ERROR MESSAGE
        $error_msg = "❌ Wrong Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Study Buddy</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="auth-container">
        <h2>Welcome Back</h2>
        
        <?php if(isset($error_msg)) echo "<div class='error-msg'>$error_msg</div>"; ?>
        
       
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            
            <input type="password" name="password" id="loginPass" placeholder="Password" required>
            
            <div style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 15px; color: #555; font-size: 0.9em;">
                <input type="checkbox" onclick="toggleLoginPass()" style="width: auto; margin-left: 5px; cursor: pointer;"> Show Password
            </div>

            <button type="submit" name="login">Login</button>
        </form>
        <p style="margin-top: 15px;">
            New here? <a href="register.php">Create an Account</a>
        </p>
    </div>

    <script>
        function toggleLoginPass() {
            var x = document.getElementById("loginPass");
            x.type = (x.type === "password") ? "text" : "password";
        }
    </script>
</body>
</html>