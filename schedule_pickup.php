<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$pageTitle = 'Schedule Pickup | ReLoop Technologies SA';
$message = '';
$userID = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['scheduledDate'] ?? '';
    $time = $_POST['scheduledTime'] ?? '';
    $wasteType = trim($_POST['wasteType'] ?? '');
    $address = trim($_POST['address'] ?? '');
    if ($date && $time && $wasteType && $address) {
        $stmt = $pdo->prepare('INSERT INTO pickups (userID, scheduledDate, scheduledTime, wasteType, address) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userID, $date, $time, $wasteType, $address]);
        $pdo->prepare('INSERT INTO notifications (userID, message, type) VALUES (?, ?, ?)')->execute([$userID, 'Your recycling pickup request has been submitted.', 'confirmation']);
        $message = 'Pickup scheduled successfully. Status is pending until an admin confirms it.';
    } else {
        $message = 'Please complete all fields.';
    }
}
$addrStmt = $pdo->prepare('SELECT address FROM users WHERE userID = ?');
$addrStmt->execute([$userID]);
$defaultAddress = $addrStmt->fetch()['address'] ?? '';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Schedule Pickup</h1><p>Choose a convenient time for recycling collection.</p></section>
<section class="section narrow">
    <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
    <form method="post" class="form-card">
        <div class="form-grid">
            <label>Date<input type="date" name="scheduledDate" required></label>
            <label>Time<input type="time" name="scheduledTime" required></label>
        </div>
        <label>Waste Type
            <select name="wasteType" required>
                <option value="">Select waste type</option>
                <option>Paper and Cardboard</option>
                <option>Plastic</option>
                <option>Glass</option>
                <option>Metal</option>
                <option>E-waste</option>
                <option>Mixed Recyclables</option>
            </select>
        </label>
        <label>Pickup Address<input type="text" name="address" value="<?= e($defaultAddress) ?>" required></label>
        <button class="btn" type="submit">Submit Pickup Request</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
