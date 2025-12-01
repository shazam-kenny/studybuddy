<?php include "includes/navbar.php"; ?>
<?php
require_once __DIR__.'/../includes/auth.php';
requireLogin();
require_once __DIR__.'/../includes/db.php';
include __DIR__.'/../templates/header.php';

$pdo = getDb();

// stats
$totalMembers = $pdo->query("SELECT COUNT(*) as c FROM members")->fetch()['c'];
$totalContrib = $pdo->query("SELECT COALESCE(SUM(amount),0) as s FROM contributions")->fetch()['s'];
?>
<div class="card">
  <h2>Dashboard</h2>
  <p>Total Members: <strong><?=htmlspecialchars($totalMembers)?></strong></p>
  <p>Total Contributions (all time): <strong><?=number_format($totalContrib,2)?></strong></p>
  <h3>Recent Contributions</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Member</th><th>Amount</th><th>Date</th></tr></thead>
    <tbody>
      <?php
      $stmt = $pdo->query("SELECT c.id, c.amount, c.contribution_date, m.full_name FROM contributions c JOIN members m ON m.id=c.member_id ORDER BY c.created_at DESC LIMIT 10");
      while ($r = $stmt->fetch()) {
          echo "<tr><td>{$r['id']}</td><td>".htmlspecialchars($r['full_name'])."</td><td>".number_format($r['amount'],2)."</td><td>{$r['contribution_date']}</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/../templates/footer.php'; ?>