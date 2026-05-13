<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Recycling Guidance | ReLoop Technologies SA';
$stmt = $pdo->query('SELECT wc.categoryName, wc.description, gc.title, gc.content FROM waste_categories wc LEFT JOIN guidance_content gc ON wc.wasteCategoryID = gc.wasteCategoryID ORDER BY wc.categoryName');
$guides = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><h1>Recycling Guidance</h1><p>Sort, clean, and prepare materials before pickup.</p></section>
<section class="section cards">
    <?php foreach ($guides as $guide): ?>
        <article class="card">
            <p class="eyebrow"><?= e($guide['categoryName']) ?></p>
            <h2><?= e($guide['title'] ?? $guide['categoryName']) ?></h2>
            <p><?= e($guide['description']) ?></p>
            <p><?= nl2br(e($guide['content'] ?? 'Guidance will be added soon.')) ?></p>
        </article>
    <?php endforeach; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
