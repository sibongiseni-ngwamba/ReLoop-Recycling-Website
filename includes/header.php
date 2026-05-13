<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';
$pageTitle = $pageTitle ?? 'ReLoop Technologies SA';
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <nav class="navbar">
        <a class="brand" href="<?= base_url('index.php') ?>"><span>ReLoop</span> Technologies SA</a>
        <button class="nav-toggle" type="button" aria-label="Toggle navigation">☰</button>
        <div class="nav-links">
            <a href="<?= base_url('index.php') ?>">Home</a>
            <a href="<?= base_url('about.php') ?>">About</a>
            <a href="<?= base_url('services.php') ?>">Services</a>
            <a href="<?= base_url('recycling_guidance.php') ?>">Guidance</a>
            <a href="<?= base_url('contact.php') ?>">Contact</a>
            <?php if (isset($_SESSION['userID'])): ?>
                <a href="<?= $isAdmin ? base_url('admin/admin_dashboard.php') : base_url('dashboard.php') ?>">Dashboard</a>
                <a class="btn small" href="<?= base_url('logout.php') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('login.php') ?>">Login</a>
                <a class="btn small" href="<?= base_url('register.php') ?>">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
