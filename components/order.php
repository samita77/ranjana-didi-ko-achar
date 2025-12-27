<section class="order section-shell" id="order">
    <div class="section-heading">
        <div class="pill">Quick order</div>
        <h2>Order now via WhatsApp</h2>
        <p class="muted">No cart needed—just enter the item and amount, and we’ll prefill your WhatsApp message.</p>
    </div>
    <div class="order-card card surface">
        <form id="orderForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="order_item">Item</label>
                    <input type="text" id="order_item" name="order_item" class="form-control" placeholder="e.g., Lapsi Achar (500g)" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="order_amount">Amount</label>
                    <input type="number" min="1" step="1" id="order_amount" name="order_amount" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="order_note">Note</label>
                    <input type="text" id="order_note" name="order_note" class="form-control" placeholder="Optional note">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button type="submit" class="btn">Send WhatsApp Order</button>
                <a class="btn btn-ghost" href="https://wa.me/?text=Hi%2C%20I%20want%20to%20order%20from%20<?php echo rawurlencode($storeName); ?>" target="_blank" rel="noopener">Open WhatsApp</a>
            </div>
        </form>
    </div>
</section>
