<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Register | ReLoop Technologies SA';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$firstName || !$lastName || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || !$address) {
        $error = 'Please complete all required fields. Password must be at least 6 characters.';
    } else {
        $stmt = $pdo->prepare('SELECT userID FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'That email address is already registered.';
        } else {
            $pdo->beginTransaction();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (firstName, lastName, email, passwordHash, phoneNumber, address) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $email, $hash, $phoneNumber, $address]);
            $userID = $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO rewards (userID) VALUES (?)')->execute([$userID]);
            $pdo->commit();
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Create Account</h1><p>Join ReLoop and start tracking your recycling impact.</p></section>
<section class="section narrow">
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form-card">
        <div class="form-grid">
            <label>First Name<input type="text" name="firstName" required></label>
            <label>Last Name<input type="text" name="lastName" required></label>
        </div>
        <label>Email<input type="email" name="email" required></label>
        <label>Phone Number<input type="text" name="phoneNumber"></label>
        <label>Address<input type="text" name="address" required></label>
        <label>Password<input type="password" name="password" required minlength="6"></label>
        <button class="btn" type="submit">Register</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
