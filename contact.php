<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Contact | ReLoop Technologies SA';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['message'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $subject && $body) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $subject, $body]);
        $message = 'Thank you. Your message has been received.';
    } else {
        $message = 'Please complete all fields with a valid email address.';
    }
}
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Contact Us</h1><p>Send ReLoop Technologies SA a message.</p></section>
<section class="section narrow">
    <?php if ($message): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
    <form method="post" class="form-card">
        <label>Name<input type="text" name="name" required></label>
        <label>Email<input type="email" name="email" required></label>
        <label>Subject<input type="text" name="subject" required></label>
        <label>Message<textarea name="message" rows="5" required></textarea></label>
        <button class="btn" type="submit">Send Message</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
