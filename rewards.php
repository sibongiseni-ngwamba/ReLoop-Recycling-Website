<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$pageTitle = 'Rewards | ReLoop Technologies SA';
$userID = current_user_id();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rewardItemID'])) {
    $itemID = (int)$_POST['rewardItemID'];
    $pdo->beginTransaction();
    $itemStmt = $pdo->prepare('SELECT * FROM reward_items WHERE rewardItemID = ? AND isActive = TRUE');
    $itemStmt->execute([$itemID]);
    $item = $itemStmt->fetch();
    $rewardStmt = $pdo->prepare('SELECT * FROM rewards WHERE userID = ? FOR UPDATE');
    $rewardStmt->execute([$userID]);
    $reward = $rewardStmt->fetch();
    if ($item && $reward && $reward['pointsBalance'] >= $item['pointsCost']) {
        $voucher = 'RL-' . strtoupper(bin2hex(random_bytes(4)));
        $pdo->prepare('UPDATE rewards SET pointsBalance = pointsBalance - ?, totalRedeemed = totalRedeemed + ?, lastUpdated = NOW() WHERE userID = ?')->execute([$item['pointsCost'], $item['pointsCost'], $userID]);
        $pdo->prepare('INSERT INTO redemption_log (userID, rewardItemID, pointsUsed, voucherCode) VALUES (?, ?, ?, ?)')->execute([$userID, $itemID, $item['pointsCost'], $voucher]);
        $pdo->commit();
        $message = 'Reward redeemed. Voucher code: ' . $voucher;
    } else {
        $pdo->rollBack();
        $message = 'You do not have enough points for that reward.';
    }
}

$rewardStmt = $pdo->prepare('SELECT * FROM rewards WHERE userID = ?');
$rewardStmt->execute([$userID]);
$reward = $rewardStmt->fetch();
$items = $pdo->query('SELECT * FROM reward_items WHERE isActive = TRUE ORDER BY pointsCost')->fetchAll();
$logStmt = $pdo->prepare('SELECT rl.*, ri.itemName FROM redemption_log rl JOIN reward_items ri ON rl.rewardItemID = ri.rewardItemID WHERE rl.userID = ? ORDER BY rl.redeemedAt DESC');
$logStmt->execute([$userID]);
$logs = $logStmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Rewards</h1><p>Redeem points earned from completed recycling pickups.</p></section>
<section class="section">
    <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
    <div class="card stat"><span>Available Points</span><strong><?= e($reward['pointsBalance'] ?? 0) ?></strong></div>
    <h2>Available Rewards</h2>
    <div class="cards">
        <?php foreach ($items as $item): ?>
            <article class="card">
                <h3><?= e($item['itemName']) ?></h3>
                <p><?= e($item['description']) ?></p>
                <p><strong><?= e($item['pointsCost']) ?> points</strong></p>
                <form method="post"><input type="hidden" name="rewardItemID" value="<?= e($item['rewardItemID']) ?>"><button class="btn" type="submit">Redeem</button></form>
            </article>
        <?php endforeach; ?>
    </div>
    <h2>Redemption History</h2>
    <div class="table-wrap">
        <table><thead><tr><th>Reward</th><th>Points</th><th>Voucher</th><th>Date</th></tr></thead><tbody>
        <?php foreach ($logs as $log): ?><tr><td><?= e($log['itemName']) ?></td><td><?= e($log['pointsUsed']) ?></td><td><?= e($log['voucherCode']) ?></td><td><?= e($log['redeemedAt']) ?></td></tr><?php endforeach; ?>
        <?php if (!$logs): ?><tr><td colspan="4">No redemptions yet.</td></tr><?php endif; ?>
        </tbody></table>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
