<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Admin Dashboard | ReLoop Technologies SA';
$stats = [
    'Total Users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn(),
    'Total Pickups' => $pdo->query('SELECT COUNT(*) FROM pickups')->fetchColumn(),
    'Completed Pickups' => $pdo->query("SELECT COUNT(*) FROM pickups WHERE status = 'completed'")->fetchColumn(),
    'Pending Pickups' => $pdo->query("SELECT COUNT(*) FROM pickups WHERE status = 'pending'")->fetchColumn(),
    'Rewards Redeemed' => $pdo->query('SELECT COUNT(*) FROM redemption_log')->fetchColumn(),
];
include __DIR__ . '/../includes/header.php';
?>
<section class="page-hero compact"><h1>Admin Dashboard</h1><p>Manage ReLoop users, pickups, rewards, and reports.</p></section>
<section class="section dashboard-grid">
    <?php foreach ($stats as $label => $value): ?><div class="card stat"><span><?= e($label) ?></span><strong><?= e($value) ?></strong></div><?php endforeach; ?>
</section>
<section class="section cards">
    <a class="card link-card" href="<?= base_url('admin/manage_users.php') ?>"><h3>Manage Users</h3></a>
    <a class="card link-card" href="<?= base_url('admin/manage_pickups.php') ?>"><h3>Manage Pickups</h3></a>
    <a class="card link-card" href="<?= base_url('admin/manage_rewards.php') ?>"><h3>Manage Rewards</h3></a>
    <a class="card link-card" href="<?= base_url('admin/reports.php') ?>"><h3>Reports</h3></a>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
