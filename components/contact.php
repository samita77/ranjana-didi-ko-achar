<section class="contact" id="contact">
    <h2>Contact Us</h2>
    <div class="contact-content">       
        <form action="contact_handler.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
            <fieldset>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
                </div>
                <button type="submit" class="btn">Send Message</button>
            </fieldset>
        </form>
    </div>
</section>
