<section class="py-14 bg-gray-50" id="order">
    <div class="max-w-6xl mx-auto px-6 space-y-4">
        <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Quick order</div>
        <h2 class="text-3xl font-bold text-slate-900">Order now via WhatsApp</h2>
        <p class="text-slate-600 max-w-2xl">No cart needed—just enter the item and amount, and we’ll prefill your WhatsApp message.</p>
    </div>
    <div class="max-w-6xl mx-auto px-6 mt-6">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form id="orderForm" class="space-y-4">
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-800" for="order_item">Item</label>
                        <input type="text" id="order_item" name="order_item" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="e.g., Lapsi Achar (500g)" required>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-800" for="order_amount">Amount</label>
                        <input type="number" min="1" step="1" id="order_amount" name="order_amount" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="Quantity" required>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-800" for="order_note">Note</label>
                        <input type="text" id="order_note" name="order_note" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="Optional note">
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-amber-500 text-white font-semibold shadow hover:bg-amber-600 transition">Send WhatsApp Order</button>
                    <a class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-slate-200 text-slate-800 font-semibold hover:bg-slate-100 transition" href="https://wa.me/?text=Hi%2C%20I%20want%20to%20order%20from%20<?php echo rawurlencode($storeName); ?>" target="_blank" rel="noopener">Open WhatsApp</a>
                </div>
            </form>
        </div>
    </div>
</section>
