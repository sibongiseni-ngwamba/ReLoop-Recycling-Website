<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Manage Rewards | ReLoop Technologies SA';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['saveReward'])) {
        $id = (int)($_POST['rewardItemID'] ?? 0);
        $name = trim($_POST['itemName'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $cost = (int)($_POST['pointsCost'] ?? 0);
        if ($name && $description && $cost > 0) {
            if ($id) {
                $pdo->prepare('UPDATE reward_items SET itemName = ?, description = ?, pointsCost = ? WHERE rewardItemID = ?')->execute([$name, $description, $cost, $id]);
                $message = 'Reward item updated.';
            } else {
                $pdo->prepare('INSERT INTO reward_items (itemName, description, pointsCost) VALUES (?, ?, ?)')->execute([$name, $description, $cost]);
                $message = 'Reward item added.';
            }
        }
    }
    if (isset($_POST['toggleActive'])) {
        $pdo->prepare('UPDATE reward_items SET isActive = NOT isActive WHERE rewardItemID = ?')->execute([(int)$_POST['rewardItemID']]);
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM reward_items WHERE rewardItemID = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}
$items = $pdo->query('SELECT * FROM reward_items ORDER BY isActive DESC, pointsCost')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="page-hero compact"><h1>Manage Rewards</h1><p>Add, edit, activate, and deactivate reward items.</p></section>
<section class="section grid two">
    <div>
        <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
        <form method="post" class="form-card">
            <h2><?= $edit ? 'Edit Reward' : 'Add Reward' ?></h2>
            <input type="hidden" name="rewardItemID" value="<?= e($edit['rewardItemID'] ?? '') ?>">
            <label>Name<input type="text" name="itemName" value="<?= e($edit['itemName'] ?? '') ?>" required></label>
            <label>Description<textarea name="description" rows="3" required><?= e($edit['description'] ?? '') ?></textarea></label>
            <label>Points Cost<input type="number" name="pointsCost" min="1" value="<?= e($edit['pointsCost'] ?? '') ?>" required></label>
            <button class="btn" name="saveReward" type="submit">Save Reward</button>
        </form>
    </div>
    <div class="table-wrap"><table><thead><tr><th>Item</th><th>Cost</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($items as $item): ?>
        <tr><td><?= e($item['itemName']) ?></td><td><?= e($item['pointsCost']) ?></td><td><?= $item['isActive'] ? 'Active' : 'Inactive' ?></td><td><a class="btn small outline" href="?edit=<?= e($item['rewardItemID']) ?>">Edit</a><form method="post" class="inline-form"><input type="hidden" name="rewardItemID" value="<?= e($item['rewardItemID']) ?>"><button class="btn small" name="toggleActive" type="submit"><?= $item['isActive'] ? 'Deactivate' : 'Activate' ?></button></form></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
