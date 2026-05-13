<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$pageTitle = 'Pickup History | ReLoop Technologies SA';
$stmt = $pdo->prepare('SELECT * FROM pickups WHERE userID = ? ORDER BY scheduledDate DESC, scheduledTime DESC');
$stmt->execute([current_user_id()]);
$pickups = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Pickup History</h1><p>Track your recycling collection requests.</p></section>
<section class="section">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Time</th><th>Waste Type</th><th>Address</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($pickups as $pickup): ?>
                <tr>
                    <td><?= e($pickup['scheduledDate']) ?></td>
                    <td><?= e(substr($pickup['scheduledTime'], 0, 5)) ?></td>
                    <td><?= e($pickup['wasteType']) ?></td>
                    <td><?= e($pickup['address']) ?></td>
                    <td><span class="badge <?= e($pickup['status']) ?>"><?= e($pickup['status']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$pickups): ?><tr><td colspan="5">No pickups found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
