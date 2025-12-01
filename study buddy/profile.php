<?php
session_start();
include 'db.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = ""; 

// --- 1. HANDLE DETAILS UPDATE ---
if (isset($_POST['update_details'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Check if username is taken
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND id != $user_id");
    if (mysqli_num_rows($check) > 0) {
        $msg = "❌ Username already taken!";
        $msg_type = "error";
    } else {
        $sql = "UPDATE users SET full_name='$full_name', email='$email', username='$username' WHERE id=$user_id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $username;
            $msg = "✅ Profile details updated!";
            $msg_type = "success";
        } else {
            $msg = "Database Error.";
            $msg_type = "error";
        }
    }
}

// --- 2. HANDLE PASSWORD UPDATE ---
if (isset($_POST['update_password'])) {
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    $query = mysqli_query($conn, "SELECT password FROM users WHERE id=$user_id");
    $db_pass = mysqli_fetch_assoc($query)['password'];

    if (!password_verify($current_pass, $db_pass)) {
        $msg = "❌ Current password is incorrect!";
        $msg_type = "error";
    } elseif ($new_pass !== $confirm_pass) {
        $msg = "❌ New passwords do not match!";
        $msg_type = "error";
    } elseif (strlen($new_pass) < 8 || !preg_match("/[a-z]/i", $new_pass) || !preg_match("/[0-9]/", $new_pass)) {
        $msg = "❌ Password must be 8+ chars with letters & numbers.";
        $msg_type = "error";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id=$user_id");
        $msg = "🔒 Password changed successfully!";
        $msg_type = "success";
    }
}

// Fetch Latest Data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .profile-section { background: #fff; padding: 25px; border-radius: 15px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .msg-box { padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body class="dashboard-body">
    
    <div class="dashboard-container" style="max-width: 600px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>👤 My Profile</h2>
            <a href="dashboard.php" style="background:#eee; color:#333; padding:10px 20px; border-radius:30px; font-weight:bold;">← Back to Dashboard</a>
        </div>

        <?php if ($msg != ""): ?>
            <div class="msg-box <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="profile-section">
            <h3 style="margin-top:0; color:#a18cd1;">📝 Personal Details</h3>
            <form method="POST">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Enter your full name">

                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="name@example.com">

                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <button type="submit" name="update_details" style="background: linear-gradient(to right, #a18cd1, #fbc2eb);">Save Changes</button>
            </form>
        </div>

        <div class="profile-section">
            <h3 style="margin-top:0; color:#ff6b6b;">🔒 Change Password</h3>
            <form method="POST">
                <label>Current Password (Required)</label>
                <input type="password" name="current_pass" id="currPass" required>

                <label>New Password <small style="color:#888;">(Min 8 chars, Letters & Numbers)</small></label>
                <input type="password" name="new_pass" id="newPass" placeholder="e.g. Pass1234" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm_pass" id="confPass" required>

                <div style="width: 100%; display: flex; justify-content: flex-start; align-items: center; margin-bottom: 15px; color: #555; font-size: 0.9em;">
                    <input type="checkbox" onclick="toggleProfilePass()" style="width: auto; margin-right: 8px; cursor: pointer;"> Show Passwords
                </div>

                <button type="submit" name="update_password" style="background: #ff6b6b;">Update Password</button>
            </form>
        </div>

    </div>

    <script>
        function toggleProfilePass() {
            var p1 = document.getElementById("currPass");
            var p2 = document.getElementById("newPass");
            var p3 = document.getElementById("confPass");
            
            // Toggle all of them at once
            var type = (p1.type === "password") ? "text" : "password";
            p1.type = type;
            p2.type = type;
            p3.type = type;
        }
    </script>
</body>
</html>