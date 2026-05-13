<?php $pageTitle = 'Home | ReLoop Technologies SA'; include __DIR__ . '/includes/header.php'; ?>
<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Recycling made simple</p>
        <h1>Schedule pickups, recycle smarter, and earn rewards.</h1>
        <p>ReLoop Technologies SA helps households and businesses reduce waste with convenient recycling pickups, practical sorting guidance, and reward points for responsible action.</p>
        <div class="actions">
            <a class="btn" href="<?= base_url('register.php') ?>">Create Account</a>
            <a class="btn outline" href="<?= base_url('login.php') ?>">Login</a>
        </div>
    </div>
</section>
<section class="section grid two">
    <div>
        <p class="eyebrow">About ReLoop</p>
        <h2>Building a cleaner circular economy in South Africa</h2>
        <p>We connect residents with organised recycling collections while teaching practical waste separation habits. The result is less landfill waste, cleaner neighbourhoods, and measurable impact.</p>
    </div>
    <div class="card highlight">
        <h3>What users can do</h3>
        <ul class="check-list">
            <li>Book recycling pickups online</li>
            <li>Track pickup history and status</li>
            <li>Learn how to sort waste correctly</li>
            <li>Redeem reward points for active recycling</li>
        </ul>
    </div>
</section>
<section class="section">
    <h2>Our Services</h2>
    <div class="cards">
        <article class="card"><h3>Pickup Scheduling</h3><p>Choose your date, time, address, and waste type for collection.</p></article>
        <article class="card"><h3>Recycling Guidance</h3><p>Read clear instructions for paper, plastic, glass, metal, and e-waste.</p></article>
        <article class="card"><h3>Reward Points</h3><p>Earn points when collections are completed and redeem them for vouchers.</p></article>
    </div>
</section>
<section class="cta-band">
    <h2>Ready to close the loop?</h2>
    <a class="btn" href="<?= base_url('schedule_pickup.php') ?>">Schedule a Pickup</a>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
