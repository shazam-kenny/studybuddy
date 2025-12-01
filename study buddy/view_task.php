<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$user_role = '';

// Get User Role
$u_res = mysqli_query($conn, "SELECT role FROM users WHERE id=$user_id");
$user_role = mysqli_fetch_assoc($u_res)['role'];

// Get Task Details
$t_res = mysqli_query($conn, "SELECT * FROM tasks WHERE id=$task_id");
$task = mysqli_fetch_assoc($t_res);

// --- HANDLE STUDENT SUBMISSION ---
if (isset($_POST['submit_assignment'])) {
    $filename = time() . "_" . $_FILES['answer_file']['name'];
    $target = "uploads/" . $filename;
    
    if (move_uploaded_file($_FILES['answer_file']['tmp_name'], $target)) {
        mysqli_query($conn, "INSERT INTO submissions (task_id, student_id, answer_file) VALUES ('$task_id', '$user_id', '$target')");
        $success = "Assignment Submitted Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Room</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <a href="dashboard.php">← Back to Dashboard</a>
        
        <div style="text-align:center; margin-bottom:30px;">
            <h1 style="color:#a18cd1;"><?php echo htmlspecialchars($task['task_name']); ?></h1>
            <p>Due Date: <?php echo $task['due_date']; ?></p>
            
            <?php if (!empty($task['question_file'])): ?>
                <div style="background:#f1f2f6; padding:20px; border-radius:10px; display:inline-block;">
                    <h3>📄 Question Paper</h3>
                    <a href="<?php echo $task['question_file']; ?>" class="download-btn" download>Download Exam/CAT</a>
                </div>
            <?php else: ?>
                <p>No file attached. Please follow instructions given in class.</p>
            <?php endif; ?>
        </div>

        <hr>

        <?php if ($user_role == 'student'): ?>
            <h3>📤 Submit Your Work</h3>
            <?php if(isset($success)) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>
            
            <?php 
            $check = mysqli_query($conn, "SELECT * FROM submissions WHERE task_id=$task_id AND student_id=$user_id");
            if (mysqli_num_rows($check) > 0): 
                $sub = mysqli_fetch_assoc($check);
            ?>
                <div style="background:#d4edda; padding:15px; border-radius:10px; color:#155724;">
                    ✅ You have submitted this assignment.<br>
                    <a href="<?php echo $sub['answer_file']; ?>">View My Submission</a>
                </div>
            <?php else: ?>
                <form method="POST" enctype="multipart/form-data" style="background:#fff; padding:20px; border:2px dashed #ccc;">
                    <label>Upload your Answer Sheet (PDF/Word/Image):</label><br><br>
                    <input type="file" name="answer_file" required><br><br>
                    <button type="submit" name="submit_assignment">Submit Assignment</button>
                </form>
            <?php endif; ?>
        
        <?php elseif ($user_role == 'admin'): ?>
            <h3>👩‍🏫 Student Submissions</h3>
            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Submitted At</th>
                    <th>Answer File</th>
                </tr>
                <?php
                $sub_sql = "SELECT submissions.*, users.username 
                            FROM submissions 
                            JOIN users ON submissions.student_id = users.id 
                            WHERE task_id = $task_id";
                $sub_res = mysqli_query($conn, $sub_sql);

                if (mysqli_num_rows($sub_res) > 0) {
                    while ($row = mysqli_fetch_assoc($sub_res)) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['submitted_at'] . "</td>";
                        echo "<td><a href='" . $row['answer_file'] . "' class='download-btn' download>Download Answer</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No submissions yet.</td></tr>";
                }
                ?>
            </table>
        <?php endif; ?>

    </div>
</body>
</html>