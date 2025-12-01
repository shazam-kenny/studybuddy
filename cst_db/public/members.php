<?php include "includes/navbar.php"; ?>
<?php
require_once __DIR__.'/../includes/auth.php';
requireLogin();
require_once __DIR__.'/../includes/db.php';
include __DIR__.'/../templates/header.php';

$pdo = getDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action']) && $_POST['action'] === 'add') {
    $full = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $joined = $_POST['joined_date'] ?? date('Y-m-d');
    $chama_id = intval($_POST['chama_id'] ?? 1);

    if ($full !== '') {
        $stmt = $pdo->prepare("INSERT INTO members (chama_id, full_name, phone, email, joined_date) VALUES (:c,:f,:p,:e,:j)");
        $stmt->execute([':c'=>$chama_id, ':f'=>$full, ':p'=>$phone, ':e'=>$email, ':j'=>$joined]);
        header('Location: members.php?added=1'); exit;
    }
}

$members = $pdo->query("SELECT m.*, c.name as chama FROM members m LEFT JOIN chamas c ON c.id = m.chama_id ORDER BY m.full_name")->fetchAll();
?>
<div class="card">
  <h2>Members</h2>
  <button onclick="document.getElementById('addForm').style.display='block'">Add Member</button>
  <div id="addForm" style="display:none; margin-top:8px;">
    <form method="POST" action="members.php">
      <input type="hidden" name="action" value="add">
      <div class="form-row"><label>Full name</label><input type="text" name="full_name" required></div>
      <div class="form-row"><label>Phone</label><input type="text" name="phone"></div>
      <div class="form-row"><label>Email</label><input type="email" name="email"></div>
      <div class="form-row"><label>Joined date</label><input type="date" name="joined_date" value="<?=date('Y-m-d')?>"></div>
      <div><button type="submit">Add</button></div>
    </form>
  </div>

  <table class="table">
    <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Joined</th></tr></thead>
    <tbody>
      <?php foreach ($members as $m): ?>
        <tr>
          <td><?=htmlspecialchars($m['id'])?></td>
          <td><?=htmlspecialchars($m['full_name'])?></td>
          <td><?=htmlspecialchars($m['phone'])?></td>
          <td><?=htmlspecialchars($m['joined_date'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/../templates/footer.php'; ?>