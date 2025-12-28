<?php
$featureCards = [
    ['icon' => 'assets/img/workflow.png', 'title' => 'Purchase Approval Workflow', 'copy' => 'Efficiently manage and approve purchase requests with ease.'],
    ['icon' => 'assets/img/analytics.png', 'title' => 'Detailed Analytics', 'copy' => 'Gain insights into sales, purchases, and inventory trends with in-depth analytics.'],
    ['icon' => 'assets/img/tracking.png', 'title' => 'Real-Time Tracking', 'copy' => 'Track inventory and purchases in real time.'],
    ['icon' => 'assets/img/reporting.png', 'title' => 'Custom Reports', 'copy' => 'Create and generate custom reports to analyze inventory performance and trends.'],
];
?>
<section class="py-14 bg-gray-50" id="features">
    <div class="max-w-6xl mx-auto px-6 space-y-4">
        <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Features</div>
        <h2 class="text-3xl font-bold text-slate-900">Key Features</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
            <?php foreach ($featureCards as $feature): ?>
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 space-y-3">
                    <img class="w-12 h-12" src="<?php echo $feature['icon']; ?>" alt="<?php echo $feature['title']; ?> Icon">
                    <h3 class="text-lg font-semibold text-slate-900"><?php echo $feature['title']; ?></h3>
                    <p class="text-sm text-slate-600"><?php echo $feature['copy']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
