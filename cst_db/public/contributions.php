<?php include "includes/navbar.php"; ?>
<?php
require_once __DIR__.'/../includes/auth.php';
requireLogin();
require_once __DIR__.'/../includes/db.php';
include __DIR__.'/../templates/header.php';

$pdo = getDb();
$stmt = $pdo->query("SELECT c.id, c.amount, c.contribution_date, c.note, m.full_name FROM contributions c JOIN members m ON m.id=c.member_id ORDER BY c.contribution_date DESC LIMIT 100");
$contribs = $stmt->fetchAll();
?>
<div class="card">
  <h2>Contributions <a href="add_contribution.php" style="float:right;">Add</a></h2>
  <table class="table">
    <thead><tr><th>ID</th><th>Member</th><th>Amount</th><th>Date</th><th>Note</th></tr></thead>
    <tbody>
      <?php foreach ($contribs as $c): ?>
        <tr>
          <td><?=htmlspecialchars($c['id'])?></td>
          <td><?=htmlspecialchars($c['full_name'])?></td>
          <td><?=number_format($c['amount'],2)?></td>
          <td><?=htmlspecialchars($c['contribution_date'])?></td>
          <td><?=htmlspecialchars($c['note'])?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/../templates/footer.php'; ?>