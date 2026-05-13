<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Reports | ReLoop Technologies SA';
$impact = $pdo->query('SELECT wc.categoryName, COUNT(wl.wasteLogID) AS logs, COALESCE(SUM(wl.weightKg),0) AS totalKg FROM waste_categories wc LEFT JOIN waste_log wl ON wc.wasteCategoryID = wl.wasteCategoryID GROUP BY wc.wasteCategoryID ORDER BY totalKg DESC')->fetchAll();
$activity = $pdo->query('SELECT u.firstName, u.lastName, u.email, COUNT(p.pickupID) AS pickups, COALESCE(r.pointsBalance,0) AS points FROM users u LEFT JOIN pickups p ON u.userID = p.userID LEFT JOIN rewards r ON u.userID = r.userID GROUP BY u.userID ORDER BY pickups DESC')->fetchAll();
$logistics = $pdo->query("SELECT status, COUNT(*) AS total FROM pickups GROUP BY status")->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="page-hero compact"><h1>Reports</h1><p>Review recycling impact, user activity, and logistics summaries.</p><button class="btn" onclick="window.print()">Print Reports</button></section>
<section class="section">
    <h2>Recycling Impact Report</h2>
    <div class="table-wrap"><table><thead><tr><th>Category</th><th>Logs</th><th>Total Weight KG</th></tr></thead><tbody><?php foreach ($impact as $row): ?><tr><td><?= e($row['categoryName']) ?></td><td><?= e($row['logs']) ?></td><td><?= e($row['totalKg']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    <h2>User Activity Report</h2>
    <div class="table-wrap"><table><thead><tr><th>User</th><th>Email</th><th>Pickups</th><th>Points</th></tr></thead><tbody><?php foreach ($activity as $row): ?><tr><td><?= e($row['firstName'] . ' ' . $row['lastName']) ?></td><td><?= e($row['email']) ?></td><td><?= e($row['pickups']) ?></td><td><?= e($row['points']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    <h2>Logistics Summary Report</h2>
    <div class="table-wrap"><table><thead><tr><th>Status</th><th>Total Pickups</th></tr></thead><tbody><?php foreach ($logistics as $row): ?><tr><td><span class="badge <?= e($row['status']) ?>"><?= e($row['status']) ?></span></td><td><?= e($row['total']) ?></td></tr><?php endforeach; ?></tbody></table></div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
