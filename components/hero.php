<section class="hero section-shell" id="home">
    <div class="hero-content surface">
        <div class="pill">Cozy single-vendor storefront</div>
        <h2>Fresh achar, one vendor, one tidy inventory.</h2>
        <p>Give <?php echo htmlspecialchars($storeName); ?> a welcoming home. Browse jars, tap WhatsApp to order, and manage stock from the same friendly dashboard.</p>
        <div class="cta-buttons">
            <a href="login/login.php" class="btn">Get Started</a>
            <a href="https://wa.me/?text=Hi%20I%27d%20like%20to%20order%20from%20<?php echo rawurlencode($storeName); ?>" class="btn btn-ghost" target="_blank" rel="noopener">Order on WhatsApp</a>
        </div>
        <div class="stat-grid">
            <div class="card stat-card">
                <p class="eyebrow">Inventory ready</p>
                <h3>Real-time stock</h3>
                <p>Track batches, reorder fast, and keep the shelves full.</p>
            </div>
            <div class="card stat-card">
                <p class="eyebrow">Database flexible</p>
                <h3>MariaDB / SQLite</h3>
                <p>Switch between MariaDB/MySQL or a simple SQLite file with one config.</p>
            </div>
            <div class="card stat-card">
                <p class="eyebrow">Storefront</p>
                <h3>Clean & white</h3>
                <p>Minimal design that lets the flavors and data shine.</p>
            </div>
        </div>
    </div>
    <div class="hero-image surface">
        <img src="undraw_Projections_re_ulc6.png" alt="Inventory Management Illustration">
    </div>
</section>
