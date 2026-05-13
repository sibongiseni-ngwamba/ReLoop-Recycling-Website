<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$pageTitle = 'Manage Users | ReLoop Technologies SA';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = (int)($_POST['userID'] ?? 0);
    if (isset($_POST['toggleActive'])) {
        $pdo->prepare('UPDATE users SET isActive = NOT isActive WHERE userID = ? AND role != ?')->execute([$userID, 'admin']);
    }
    if (isset($_POST['role'])) {
        $role = in_array($_POST['role'], ['user', 'agent', 'admin'], true) ? $_POST['role'] : 'user';
        $pdo->prepare('UPDATE users SET role = ? WHERE userID = ?')->execute([$role, $userID]);
    }
}

$search = trim($_GET['search'] ?? '');
if ($search) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE firstName LIKE ? OR lastName LIKE ? OR email LIKE ? ORDER BY createdAt DESC');
    $like = "%$search%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM users ORDER BY createdAt DESC');
}
$users = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<section class="page-hero compact"><h1>Manage Users</h1><p>Search, activate, deactivate, and adjust roles.</p></section>
<section class="section">
    <form class="toolbar" method="get"><input type="search" name="search" placeholder="Search users" value="<?= e($search) ?>"><button class="btn" type="submit">Search</button></form>
    <div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= e($user['firstName'] . ' ' . $user['lastName']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['phoneNumber']) ?></td>
            <td>
                <form method="post" class="inline-form"><input type="hidden" name="userID" value="<?= e($user['userID']) ?>"><select name="role" onchange="this.form.submit()"><option <?= $user['role']==='user'?'selected':'' ?>>user</option><option <?= $user['role']==='agent'?'selected':'' ?>>agent</option><option <?= $user['role']==='admin'?'selected':'' ?>>admin</option></select></form>
            </td>
            <td><?= $user['isActive'] ? 'Active' : 'Inactive' ?></td>
            <td><form method="post"><input type="hidden" name="userID" value="<?= e($user['userID']) ?>"><button class="btn small" name="toggleActive" type="submit"><?= $user['isActive'] ? 'Deactivate' : 'Activate' ?></button></form></td>
        </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
