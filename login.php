<?php
require_once __DIR__ . '/includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = 'Login | ReLoop Technologies SA';
$error = '';
$notice = isset($_GET['registered']) ? 'Registration successful. Please log in.' : '';

function rehash_seed_account(PDO $pdo, array $user, string $password): array {
    $seedPasswords = [
        'admin@reloop.co.za' => 'Admin@123',
        'user@reloop.co.za' => 'User@123',
    ];
    if (strpos($user['passwordHash'], 'NEEDS_HASH:') === 0 && isset($seedPasswords[$user['email']]) && hash_equals($seedPasswords[$user['email']], $password)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE users SET passwordHash = ? WHERE userID = ?')->execute([$newHash, $user['userID']]);
        $user['passwordHash'] = $newHash;
    }
    return $user;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND isActive = TRUE');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        $user = rehash_seed_account($pdo, $user, $password);
    }
    if ($user && password_verify($password, $user['passwordHash'])) {
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['role'] = $user['role'];
        header('Location: ' . ($user['role'] === 'admin' ? base_url('admin/admin_dashboard.php') : base_url('dashboard.php')));
        exit;
    }
    $error = 'Invalid email or password, or the account is inactive.';
}
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Login</h1><p>Access your ReLoop dashboard.</p></section>
<section class="section narrow">
    <?php if ($notice): ?><div class="alert"><?= e($notice) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form-card">
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="btn" type="submit">Login</button>
        <p class="muted">Sample admin: admin@reloop.co.za / Admin@123</p>
        <p class="muted">Sample user: user@reloop.co.za / User@123</p>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
