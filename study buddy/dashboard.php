<?php
session_start();
include 'db.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$current_user = mysqli_fetch_assoc($user_query);
$my_role = $current_user['role'];

// --- 1. HANDLE UPLOADS ---
if (isset($_POST['upload_file']) && $my_role == 'admin') {
    $filename = time()."_".$_FILES['file']['name'];
    $target = "uploads/" . $filename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        mysqli_query($conn, "INSERT INTO files (user_id, filename, filepath) VALUES ('$user_id', '$filename', '$target')");
    }
}

// --- 2. HANDLE ADD TASK ---
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    
    $question_file = NULL;
    if (!empty($_FILES['question_file']['name'])) {
        $filename = time() . "_" . $_FILES['question_file']['name'];
        $target = "uploads/" . $filename;
        if (move_uploaded_file($_FILES['question_file']['tmp_name'], $target)) { $question_file = $target; }
    }

    $sql = "INSERT INTO tasks (user_id, task_name, due_date, priority, question_file) 
            VALUES ('$user_id', '$task_name', '$due_date', '$priority', '$question_file')";
    mysqli_query($conn, $sql);
}

// --- 3. HANDLE DELETE / COMPLETE (Personal Only) ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM tasks WHERE id=$id AND user_id=$user_id");
    header("Location: dashboard.php");
}
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    mysqli_query($conn, "UPDATE tasks SET status='Completed' WHERE id=$id AND user_id=$user_id");
    header("Location: dashboard.php");
}

// --- 4. FETCH TASKS & CALCULATE PROGRESS DYNAMICALLY ---
// We fetch ALL tasks first, then calculate progress in a loop
$tasks_array = [];
$total_tasks = 0;
$completed_tasks = 0;
$overdue_count = 0;

$sql = "SELECT tasks.*, users.username, users.role 
        FROM tasks 
        JOIN users ON tasks.user_id = users.id 
        WHERE tasks.user_id = $user_id 
        OR users.role = 'admin'
        ORDER BY tasks.due_date ASC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    // Determine the TRUE status for THIS student
    $my_status = 'Pending';
    
    if ($row['role'] == 'admin') {
        // If it's an admin task, check if I submitted an answer
        $t_id = $row['id'];
        $check_sub = mysqli_query($conn, "SELECT * FROM submissions WHERE task_id=$t_id AND student_id=$user_id");
        if (mysqli_num_rows($check_sub) > 0) {
            $my_status = 'Completed';
        }
    } else {
        // If it's my personal task, trust the database status
        $my_status = $row['status'];
    }

    // Add to counters
    $total_tasks++;
    if ($my_status == 'Completed') {
        $completed_tasks++;
    }

    // Check Overdue
    if ($my_status == 'Pending' && time() > strtotime($row['due_date'])) {
        $overdue_count++;
    }

    // Save modified row to array to display later
    $row['my_status'] = $my_status;
    $tasks_array[] = $row;
}

// Calculate Percentage
$progress = ($total_tasks > 0) ? round(($completed_tasks / $total_tasks) * 100) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="dashboard-body">
    
    <div id="toast" class="notification-toast">
        🔔 Alert: You have <?php echo $overdue_count; ?> overdue tasks!
    </div>

    <div class="dashboard-container">
       <div class="nav-header">
            <div>
                <h2>🚀 Hello, <?php echo htmlspecialchars($current_user['username']); ?>!</h2>
                <span class="<?php echo ($my_role == 'admin') ? 'badge-admin' : 'badge-student'; ?>">
                    <?php echo ucfirst($my_role); ?>
                </span>
            </div>
            
            <div style="display:flex; gap:10px;">
                <a href="profile.php" style="background: #a18cd1; color: white; padding: 10px 20px; border-radius: 30px; font-weight: bold;">👤 My Profile</a>
                <a href="logout.php" style="background: #ff6b6b; color: white; padding: 10px 20px; border-radius: 30px; font-weight: bold;">Logout</a>
            </div>
        </div>

        <h3>Your Progress</h3>
        <div class="progress-container">
            <div class="progress-bar" style="width: <?php echo $progress; ?>%;">
                <?php echo $progress; ?>%
            </div>
        </div>
        <br>

        <div style="background:#fff; border:1px solid #eee; padding:20px; border-radius:15px; margin-bottom: 30px;">
            <h3 style="margin-top:0;">📚 Study Materials</h3>
            <?php if ($my_role == 'admin'): ?>
                <form method="POST" enctype="multipart/form-data" style="background:#f1f2f6; padding:15px; border-radius:10px; display:flex; gap:10px; align-items:center;">
                    <input type="file" name="file" required style="background:white; padding:10px;">
                    <button type="submit" name="upload_file">Upload</button>
                </form>
            <?php endif; ?>
            <div class="file-list">
                <?php
                $file_res = mysqli_query($conn, "SELECT * FROM files ORDER BY uploaded_at DESC LIMIT 5");
                while ($f = mysqli_fetch_assoc($file_res)) {
                    echo "<div class='file-card' style='padding:10px; margin-top:5px;'><span>📄 " . htmlspecialchars($f['filename']) . "</span><a href='" . $f['filepath'] . "' class='download-btn' download>Download</a></div>";
                }
                ?>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin-bottom: 30px; border: 2px dashed #d1d8e0;">
            <h3><?php echo ($my_role == 'admin') ? '📢 Post Exam / Assignment' : '➕ Add Personal Task'; ?></h3>
            <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="task_name" placeholder="Task Name" required style="flex: 2;">
                <input type="date" name="due_date" required style="flex: 1;">
                <select name="priority" style="flex: 1;">
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Low">Low</option>
                </select>
                <?php if ($my_role == 'admin'): ?>
                    <div style="flex: 100%;"><label>Attach Question Paper:</label><input type="file" name="question_file"></div>
                <?php endif; ?>
                <button type="submit" name="add_task" style="width: 100%;">Post Task</button>
            </form>
        </div>

        <h3>📌 Task Board</h3>
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tasks_array as $row) {
                    // Date Logic
                    $due_date = strtotime($row['due_date']);
                    $days_left = round(($due_date - time()) / (60 * 60 * 24));
                    $formatted_date = date("M j, Y", $due_date);

                    if ($row['my_status'] == 'Completed') {
                        $date_text = "<span class='safe'>Completed!</span>";
                        $status_text = "<strong style='color:green'>Done ✅</strong>";
                    } elseif ($days_left < 0) {
                        $date_text = "<span class='overdue'>Overdue: $formatted_date</span>";
                        $status_text = "<span class='overdue'>Pending</span>";
                    } else {
                        $date_text = "$formatted_date";
                        $status_text = "Pending";
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['task_name']) . "</td>";
                    echo "<td><span class='priority-" . strtolower($row['priority']) . "'>" . $row['priority'] . "</span></td>";
                    echo "<td>$date_text</td>";
                    echo "<td>$status_text</td>";
                    
                    // ACTIONS
                    echo "<td>";
                    // 1. OPEN BUTTON (Takes you to view_task.php)
                    echo "<a href='view_task.php?id=" . $row['id'] . "' style='background:#a18cd1; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;'>📂 Open</a> ";

                    // 2. Personal Task Actions
                    if ($row['user_id'] == $user_id) {
                         if ($row['status'] != 'Completed') {
                             echo "<a href='dashboard.php?complete=" . $row['id'] . "' style='text-decoration:none; margin-left:5px;'>✅</a>";
                         }
                         echo "<a href='dashboard.php?delete=" . $row['id'] . "' onclick='return confirmDelete()' style='text-decoration:none; color:red; margin-left:5px;'>✖</a>";
                    }
                    echo "</td></tr>";
                }
                
                if (count($tasks_array) == 0) echo "<tr><td colspan='5' style='text-align:center'>No tasks found.</td></tr>";
                ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
    <script>
        if (<?php echo $overdue_count; ?> > 0) {
            document.getElementById("toast").className = "notification-toast show";
            setTimeout(function(){ document.getElementById("toast").className = "notification-toast"; }, 5000);
        }
    </script>
</body>
</html>