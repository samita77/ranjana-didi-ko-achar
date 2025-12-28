<section class="py-16" id="home">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 items-center px-6">
        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-4">
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Cozy single-vendor storefront</div>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight">Fresh achar, one vendor, one tidy inventory.</h2>
            <p class="text-slate-600">Give <?php echo htmlspecialchars($storeName); ?> a welcoming home. Browse jars, tap WhatsApp to order, and manage stock from the same friendly dashboard.</p>
            <div class="flex flex-wrap gap-3">
                <a href="login/login.php" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-amber-500 text-white font-semibold shadow hover:bg-amber-600 transition">Get Started</a>
                <a href="https://wa.me/?text=Hi%20I%27d%20like%20to%20order%20from%20<?php echo rawurlencode($storeName); ?>" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-slate-200 text-slate-800 font-semibold hover:bg-slate-100 transition" target="_blank" rel="noopener">Order on WhatsApp</a>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 pt-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Inventory ready</p>
                    <h3 class="text-lg font-semibold text-slate-900">Real-time stock</h3>
                    <p class="text-sm text-slate-600">Track batches, reorder fast, and keep the shelves full.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Database flexible</p>
                    <h3 class="text-lg font-semibold text-slate-900">MariaDB / SQLite</h3>
                    <p class="text-sm text-slate-600">Switch between MariaDB/MySQL or a simple SQLite file with one config.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Storefront</p>
                    <h3 class="text-lg font-semibold text-slate-900">Clean & white</h3>
                    <p class="text-sm text-slate-600">Minimal design that lets the flavors and data shine.</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <img class="w-full h-auto" src="assets/img/undraw_Projections_re_ulc6.png" alt="Inventory Management Illustration">
        </div>
    </div>
</section>
