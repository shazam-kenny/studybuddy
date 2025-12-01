<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Study Buddy</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="auth-container">
        <h2>Create Account</h2>
        
        <?php if(isset($error_msg)) echo "<div class='error-msg'>$error_msg</div>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Choose a Username" required value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            
            <input type="password" name="password" id="regPass" placeholder="Password (8+ chars, letters & numbers)" required>
            
            <div style="width: 100%; display: flex; justify-content: flex-start; align-items: center; margin-bottom: 15px; color: #555; font-size: 0.9em;">
                <input type="checkbox" onclick="toggleRegPass()" style="width: auto; margin-right: 8px; cursor: pointer;"> Show Password
            </div>
            
            <label style="display:block; text-align:left; margin-top:10px; color:#666;">I am a:</label>
            <select name="role">
                <option value="student">Student</option>
                <option value="admin">Teacher (Admin)</option>
            </select>

            <button type="submit" name="register" style="margin-top:10px;">Sign Up</button>
        </form>

        <?php
        if (isset($_POST['register'])) {
            $user = mysqli_real_escape_string($conn, $_POST['username']);
            $password_input = $_POST['password'];
            $role = $_POST['role'];

            // VALIDATION
            if (strlen($password_input) < 8) {
                $error_msg = "⚠️ Password is too short! It needs at least 8 characters.";
            } 
            elseif (!preg_match("/[a-z]/i", $password_input)) {
                $error_msg = "⚠️ Password needs at least one letter!";
            }
            elseif (!preg_match("/[0-9]/", $password_input)) {
                $error_msg = "⚠️ Password needs at least one number!";
            } 
            else {
                $pass = password_hash($password_input, PASSWORD_DEFAULT);
                $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
                
                if (mysqli_num_rows($check) > 0) {
                    $error_msg = "❌ Username already taken! Try another.";
                } else {
                    $sql = "INSERT INTO users (username, password, role) VALUES ('$user', '$pass', '$role')";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Account Created! Login now.'); window.location='index.php';</script>";
                    } else {
                        $error_msg = "Database Error.";
                    }
                }
            }
        }
        ?>
        <p style="margin-top: 15px;">
            Already have an account? <a href="index.php">Login</a>
        </p>
    </div>

    <script>
        function toggleRegPass() {
            var x = document.getElementById("regPass");
            x.type = (x.type === "password") ? "text" : "password";
        }
    </script>
</body>
</html>