<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Manage Pickups | ReLoop Technologies SA';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickupID = (int)($_POST['pickupID'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['pending', 'confirmed', 'completed', 'cancelled'], true) ? $_POST['status'] : 'pending';
    $agentID = $_POST['agentID'] !== '' ? (int)$_POST['agentID'] : null;
    $oldStmt = $pdo->prepare('SELECT userID, status FROM pickups WHERE pickupID = ?');
    $oldStmt->execute([$pickupID]);
    $old = $oldStmt->fetch();
    if ($old) {
        $pdo->beginTransaction();
        $pdo->prepare('UPDATE pickups SET status = ?, agentID = ? WHERE pickupID = ?')->execute([$status, $agentID, $pickupID]);
        if ($status === 'completed' && $old['status'] !== 'completed') {
            $points = 50;
            $pdo->prepare('UPDATE rewards SET pointsBalance = pointsBalance + ?, totalEarned = totalEarned + ?, lastUpdated = NOW() WHERE userID = ?')->execute([$points, $points, $old['userID']]);
            $catStmt = $pdo->prepare('SELECT wasteCategoryID FROM waste_categories WHERE categoryName LIKE ? LIMIT 1');
            $catStmt->execute(['%' . strtok((string)($_POST['wasteType'] ?? ''), ' ') . '%']);
            $catID = $catStmt->fetchColumn() ?: 1;
            $pdo->prepare('INSERT INTO waste_log (userID, pickupID, wasteCategoryID, weightKg) VALUES (?, ?, ?, ?)')->execute([$old['userID'], $pickupID, $catID, 5.00]);
        }
        $pdo->commit();
        $message = 'Pickup updated successfully.';
    }
}

$agents = $pdo->query('SELECT agentID, name, assignedZone FROM agents WHERE isAvailable = TRUE ORDER BY name')->fetchAll();
$filter = $_GET['status'] ?? '';
$sql = "SELECT p.*, u.firstName, u.lastName, a.name AS agentName FROM pickups p JOIN users u ON p.userID = u.userID LEFT JOIN agents a ON p.agentID = a.agentID";
if (in_array($filter, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
    $stmt = $pdo->prepare($sql . ' WHERE p.status = ? ORDER BY p.scheduledDate DESC');
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query($sql . ' ORDER BY p.scheduledDate DESC');
}
$pickups = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="page-hero compact"><h1>Manage Pickups</h1><p>Assign agents and update collection statuses.</p></section>
<section class="section">
    <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
    <form class="toolbar" method="get"><select name="status"><option value="">All statuses</option><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= e($s) ?>" <?= $filter===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="btn" type="submit">Filter</button></form>
    <div class="table-wrap"><table><thead><tr><th>User</th><th>Date</th><th>Waste</th><th>Address</th><th>Agent</th><th>Status</th><th>Save</th></tr></thead><tbody>
    <?php foreach ($pickups as $pickup): ?>
        <?php $formID = 'pickupForm' . (int)$pickup['pickupID']; ?>
        <tr>
            <td><?= e($pickup['firstName'] . ' ' . $pickup['lastName']) ?><form id="<?= e($formID) ?>" method="post"></form></td>
            <td><?= e($pickup['scheduledDate']) ?> <?= e(substr($pickup['scheduledTime'],0,5)) ?></td>
            <td><?= e($pickup['wasteType']) ?><input form="<?= e($formID) ?>" type="hidden" name="wasteType" value="<?= e($pickup['wasteType']) ?>"></td>
            <td><?= e($pickup['address']) ?></td>
            <td><select form="<?= e($formID) ?>" name="agentID"><option value="">Unassigned</option><?php foreach ($agents as $agent): ?><option value="<?= e($agent['agentID']) ?>" <?= $pickup['agentID']==$agent['agentID']?'selected':'' ?>><?= e($agent['name']) ?> (<?= e($agent['assignedZone']) ?>)</option><?php endforeach; ?></select></td>
            <td><select form="<?= e($formID) ?>" name="status"><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= e($s) ?>" <?= $pickup['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select></td>
            <td><input form="<?= e($formID) ?>" type="hidden" name="pickupID" value="<?= e($pickup['pickupID']) ?>"><button form="<?= e($formID) ?>" class="btn small" type="submit">Update</button></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
