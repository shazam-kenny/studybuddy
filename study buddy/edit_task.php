<?php
session_start();
include 'db.php';

// Security: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if an ID is provided
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    
    // Fetch the specific task to edit
    // Security: Ensure only the creator or an Admin can edit (if you want admins to edit everything, remove the 'AND user_id' part)
    $sql = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id"; 
    $result = mysqli_query($conn, $sql);
    $task = mysqli_fetch_assoc($result);

    if (!$task) {
        die("Task not found or access denied!");
    }
} else {
    header("Location: dashboard.php");
}

// --- HANDLE UPDATE LOGIC ---
if (isset($_POST['update_task'])) {
    $task_name = $_POST['task_name'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];

    $update_sql = "UPDATE tasks SET task_name='$task_name', due_date='$due_date', priority='$priority' WHERE id=$task_id";
    
    if (mysqli_query($conn, $update_sql)) {
        header("Location: dashboard.php"); // Go back to dashboard on success
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="dashboard-body">
    <div class="auth-container" style="width: 500px;">
        <h2>✏️ Edit Task</h2>
        
        <form method="POST">
            <label>Task Name</label>
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>

            <label>Due Date</label>
            <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" required>

            <label>Priority</label>
            <select name="priority">
                <option value="High" <?php if($task['priority']=='High') echo 'selected'; ?>>High Priority</option>
                <option value="Medium" <?php if($task['priority']=='Medium') echo 'selected'; ?>>Medium Priority</option>
                <option value="Low" <?php if($task['priority']=='Low') echo 'selected'; ?>>Low Priority</option>
            </select>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" name="update_task" style="background: linear-gradient(to right, #11998e, #38ef7d);">Save Changes</button>
                <a href="dashboard.php" style="width:100%; text-align:center; padding:18px; background:#ccc; border-radius:50px; color:black; font-weight:bold;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>