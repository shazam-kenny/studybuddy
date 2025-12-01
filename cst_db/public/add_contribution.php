<?php include "includes/navbar.php"; ?>
<?php
require_once __DIR__.'/../includes/auth.php';
requireLogin();
require_once __DIR__.'/../includes/db.php';
include __DIR__.'/../templates/header.php';

$pdo = getDb();
$members = $pdo->query("SELECT id, full_name FROM members ORDER BY full_name")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = intval($_POST['member_id'] ?? 0);
    $amount = floatval($_POST['amount'] ?? 0);
    $date = $_POST['date'] ?? date('Y-m-d');
    $note = trim($_POST['note'] ?? '');

    if ($member_id <= 0) $errors[] = "Select member.";
    if ($amount <= 0) $errors[] = "Enter valid amount.";

    if (empty($errors)) {
        // get chama_id from member
        $stmt = $pdo->prepare("SELECT chama_id FROM members WHERE id = :m");
        $stmt->execute([':m'=>$member_id]);
        $m = $stmt->fetch();
        $chama_id = $m ? $m['chama_id'] : 1;

        $stmt = $pdo->prepare("INSERT INTO contributions (member_id, chama_id, amount, contribution_date, recorded_by, note) VALUES (:m,:c,:a,:d,:r,:n)");
        $stmt->execute([
            ':m'=>$member_id, ':c'=>$chama_id, ':a'=>$amount, ':d'=>$date, ':r'=>$_SESSION['user_id'], ':n'=>$note
        ]);
        header('Location: contributions.php?added=1'); exit;
    }
}
?>

<div class="card">
  <h2>Record Contribution</h2>
  <?php foreach ($errors as $e) echo "<div style='color:red;'>".htmlspecialchars($e)."</div>"; ?>
  <form method="POST" action="add_contribution.php">
    <div class="form-row">
      <label>Member</label>
      <select name="member_id" required>
        <option value="">--select--</option>
        <?php foreach ($members as $m): ?>
          <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-row">
      <label>Amount</label>
      <input type="number" step="0.01" name="amount" required min="0.01">
    </div>
    <div class="form-row">
      <label>Date</label>
      <input type="date" name="date" value="<?=date('Y-m-d')?>" required>
    </div>
    <div class="form-row">
      <label>Note</label>
      <input type="text" name="note">
    </div>
    <div><button type="submit">Save</button></div>
  </form>
</div>

<?php include __DIR__.'/../templates/footer.php'; ?>