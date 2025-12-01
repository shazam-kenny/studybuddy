<?php include "includes/navbar.php"; ?>
<?php
require_once __DIR__.'/../includes/auth.php';
requireLogin();
require_once __DIR__.'/../includes/db.php';
include __DIR__.'/../templates/header.php';

$pdo = getDb();

// Get monthly totals for the last 12 months
$stmt = $pdo->prepare("
SELECT DATE_FORMAT(contribution_date, '%Y-%m') as m, SUM(amount) as total
FROM contributions
WHERE contribution_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
GROUP BY m
ORDER BY m
");
$stmt->execute();
$rows = $stmt->fetchAll();

$labels = [];
$data = [];
foreach ($rows as $r) {
    $labels[] = $r['m'];
    $data[] = (float)$r['total'];
}
?>
<div class="card">
  <h2>Reports - Monthly Contributions (last 12 months)</h2>
  <canvas id="monthlyChart" width="600" height="300"></canvas>
  <script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Total contributions',
          data: <?= json_encode($data) ?>,
        }]
      },
      options: {}
    });
  </script>
</div>

<div class="card">
  <h3>Member Balances</h3>
  <table class="table">
    <thead><tr><th>Member</th><th>Total Contributed</th><th>Total Out</th><th>Balance</th></tr></thead>
    <tbody>
      <?php
      $stmt = $pdo->query("
        SELECT m.id, m.full_name,
          COALESCE((SELECT SUM(amount) FROM contributions WHERE member_id = m.id),0) as total_contrib,
          COALESCE((SELECT SUM(amount) FROM transactions WHERE member_id = m.id AND type IN ('withdrawal','loan')),0) as total_out,
          COALESCE((SELECT SUM(amount) FROM transactions WHERE member_id = m.id AND type = 'adjustment'),0) as total_adj
        FROM members m
        ORDER BY m.full_name
      ");
      while ($r = $stmt->fetch()) {
          $balance = $r['total_contrib'] - $r['total_out'] + $r['total_adj'];
          echo "<tr><td>".htmlspecialchars($r['full_name'])."</td><td>".number_format($r['total_contrib'],2)."</td><td>".number_format($r['total_out'],2)."</td><td>".number_format($balance,2)."</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<?php include __DIR__.'/../templates/footer.php'; ?>