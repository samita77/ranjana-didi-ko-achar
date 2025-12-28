<section class="py-14 bg-gray-50" id="contact">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-slate-900 text-center">Contact Us</h2>
        <div class="mt-6 max-w-3xl mx-auto bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <form action="contact_handler.php" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-800" for="name">Name</label>
                        <input type="text" id="name" name="name" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="Enter your name" required>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-800" for="email">Email</label>
                        <input type="email" id="email" name="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-800" for="message">Message</label>
                    <textarea id="message" name="message" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none" placeholder="Enter your message" required></textarea>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-amber-500 text-white font-semibold shadow hover:bg-amber-600 transition">Send Message</button>
            </form>
        </div>
    </div>
</section>
