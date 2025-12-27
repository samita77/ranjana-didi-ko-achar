<?php
$storeItems = [
    [
        'name' => 'Lapsi Achar',
        'badge' => 'Mild',
        'description' => 'Sweet & tangy with hand-ground spices. Perfect for snack time.',
        'meta' => ['Stock synced', 'Ready to ship'],
    ],
    [
        'name' => 'Titaura Mix',
        'badge' => 'Signature',
        'description' => 'Colorful mix for the real fans of homemade flavors.',
        'meta' => ['Batch tracked', 'Single vendor'],
        'accent' => true,
    ],
    [
        'name' => 'Chilli Garlic',
        'badge' => 'Spicy',
        'description' => 'Cozy heat with roasted garlic—pairs with anything crispy.',
        'meta' => ['Fresh jars', 'Low on waste'],
    ],
];
?>
<section class="storefront section-shell" id="store">
    <div class="section-heading">
        <div class="pill">Shop</div>
        <h2><?php echo htmlspecialchars($storeName); ?> best-selling achar jars</h2>
        <p class="muted">Highlight a single vendor’s catalog with consistent product cards, quick details, and tap-to-WhatsApp actions.</p>
    </div>
    <div class="feature-cards">
        <?php foreach ($storeItems as $item): ?>
            <div class="card store-card">
                <div class="badge <?php echo !empty($item['accent']) ? 'badge-accent' : ''; ?>"><?php echo $item['badge']; ?></div>
                <h3><?php echo $item['name']; ?></h3>
                <p><?php echo $item['description']; ?></p>
                <div class="store-meta">
                    <?php for ($i = 0; $i < count($item['meta']); $i++): ?>
                        <span><?php echo $item['meta'][$i]; ?></span>
                        <?php if ($i < count($item['meta']) - 1): ?>
                            <span class="dot"></span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <a class="btn btn-ghost store-btn" href="https://wa.me/?text=Hi%2C%20I%20want%20to%20order%20<?php echo rawurlencode($item['name']); ?>" target="_blank" rel="noopener">Order on WhatsApp</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
