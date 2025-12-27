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
<section class="py-14 bg-white" id="store">
    <div class="max-w-6xl mx-auto px-6 space-y-4">
        <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Shop</div>
        <h2 class="text-3xl font-bold text-slate-900"><?php echo htmlspecialchars($storeName); ?> best-selling achar jars</h2>
        <p class="text-slate-600">Highlight a single vendor’s catalog with consistent product cards, quick details, and tap-to-WhatsApp actions.</p>
    </div>
    <div class="max-w-6xl mx-auto px-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-6">
        <?php foreach ($storeItems as $item): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 flex flex-col gap-3">
                <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide <?php echo !empty($item['accent']) ? 'text-slate-800 bg-slate-100' : 'text-amber-600 bg-amber-50'; ?> border border-amber-200 px-3 py-1 rounded-full"><?php echo $item['badge']; ?></div>
                <h3 class="text-xl font-semibold text-slate-900"><?php echo $item['name']; ?></h3>
                <p class="text-slate-600 text-sm"><?php echo $item['description']; ?></p>
                <div class="flex items-center gap-2 flex-wrap text-slate-500 text-sm">
                    <?php for ($i = 0; $i < count($item['meta']); $i++): ?>
                        <span class="inline-flex items-center gap-1"><?php echo $item['meta'][$i]; ?></span>
                        <?php if ($i < count($item['meta']) - 1): ?>
                            <span class="w-1 h-1 rounded-full bg-amber-500 inline-flex"></span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <a class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-slate-200 text-slate-800 font-semibold hover:bg-slate-100 transition" href="https://wa.me/?text=Hi%2C%20I%20want%20to%20order%20<?php echo rawurlencode($item['name']); ?>" target="_blank" rel="noopener">Order on WhatsApp</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
