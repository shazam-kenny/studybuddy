<?php include "includes/navbar.php"; ?>
<?php
// includes/functions.php
require_once __DIR__ . '/db.php';

// Get members for a specific chama
function getMembers($chama_id) {
    $pdo = getDb();
    $stmt = $pdo->prepare("SELECT * FROM members WHERE chama_id = :c ORDER BY full_name");
    $stmt->execute([':c' => $chama_id]);
    return $stmt->fetchAll();
}

// Compute balance for a member (contributions - withdrawals/loans)
function getMemberBalance($member_id) {
    $pdo = getDb();
    $stmt = $pdo->prepare("
        SELECT 
          COALESCE((SELECT SUM(amount) FROM contributions WHERE member_id = :m), 0) as total_contrib,
          COALESCE((SELECT SUM(amount) FROM transactions WHERE member_id = :m AND type IN ('withdrawal','loan')), 0) as total_out,
          COALESCE((SELECT SUM(amount) FROM transactions WHERE member_id = :m AND type = 'adjustment'), 0) as total_adjust
    ");
    $stmt->execute([':m' => $member_id]);
    $r = $stmt->fetch();
    $balance = $r['total_contrib'] - $r['total_out'] + $r['total_adjust'];
    return $balance;
}