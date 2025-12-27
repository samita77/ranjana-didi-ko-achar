<?php
$featureCards = [
    ['icon' => 'assets/img/workflow.png', 'title' => 'Purchase Approval Workflow', 'copy' => 'Efficiently manage and approve purchase requests with ease.'],
    ['icon' => 'assets/img/analytics.png', 'title' => 'Detailed Analytics', 'copy' => 'Gain insights into sales, purchases, and inventory trends with in-depth analytics.'],
    ['icon' => 'assets/img/tracking.png', 'title' => 'Real-Time Tracking', 'copy' => 'Track inventory and purchases in real time.'],
    ['icon' => 'assets/img/reporting.png', 'title' => 'Custom Reports', 'copy' => 'Create and generate custom reports to analyze inventory performance and trends.'],
];
?>
<section class="features" id="features">
    <h2>Key Features</h2>
    <div class="feature-cards">
        <?php foreach ($featureCards as $feature): ?>
            <div class="card">
                <img src="<?php echo $feature['icon']; ?>" alt="<?php echo $feature['title']; ?> Icon">
                <h3><?php echo $feature['title']; ?></h3>
                <p><?php echo $feature['copy']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
