<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$pageTitle = 'Dashboard | ReLoop Technologies SA';
$userID = current_user_id();
$pickupStmt = $pdo->prepare("SELECT * FROM pickups WHERE userID = ? AND status IN ('pending','confirmed') ORDER BY scheduledDate, scheduledTime LIMIT 1");
$pickupStmt->execute([$userID]);
$upcoming = $pickupStmt->fetch();
$rewardStmt = $pdo->prepare('SELECT pointsBalance FROM rewards WHERE userID = ?');
$rewardStmt->execute([$userID]);
$points = (int)($rewardStmt->fetch()['pointsBalance'] ?? 0);
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Welcome, <?= e($_SESSION['firstName']) ?></h1><p>Your recycling activity at a glance.</p></section>
<section class="section dashboard-grid">
    <div class="card stat"><span>Reward Points</span><strong><?= e($points) ?></strong></div>
    <div class="card">
        <h2>Upcoming Pickup</h2>
        <?php if ($upcoming): ?>
            <p><strong><?= e($upcoming['scheduledDate']) ?></strong> at <?= e(substr($upcoming['scheduledTime'], 0, 5)) ?></p>
            <p><?= e($upcoming['wasteType']) ?> - <span class="badge <?= e($upcoming['status']) ?>"><?= e($upcoming['status']) ?></span></p>
        <?php else: ?>
            <p>No upcoming pickup scheduled.</p>
        <?php endif; ?>
    </div>
</section>
<section class="section cards">
    <a class="card link-card" href="<?= base_url('schedule_pickup.php') ?>"><h3>Schedule Pickup</h3><p>Book a new collection.</p></a>
    <a class="card link-card" href="<?= base_url('pickup_history.php') ?>"><h3>Pickup History</h3><p>View all your requests.</p></a>
    <a class="card link-card" href="<?= base_url('rewards.php') ?>"><h3>Rewards</h3><p>Redeem your points.</p></a>
    <a class="card link-card" href="<?= base_url('recycling_guidance.php') ?>"><h3>Guidance</h3><p>Sort waste correctly.</p></a>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
