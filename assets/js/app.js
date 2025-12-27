document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const section = document.querySelector(this.getAttribute('href'));
        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
const navLinks = document.querySelector('.nav-links');
const navOverlay = document.createElement('div');
navOverlay.className = 'fixed inset-0 bg-black/40 hidden z-40';
document.body.appendChild(navOverlay);

function toggleMenu() {
    if (!navLinks) return;
    navLinks.classList.toggle('hidden');
    navOverlay.classList.toggle('hidden');
}

function closeMenu() {
    if (!navLinks) return;
    navLinks.classList.add('hidden');
    navOverlay.classList.add('hidden');
}

if (mobileNavToggle) mobileNavToggle.addEventListener('click', toggleMenu);
navOverlay.addEventListener('click', closeMenu);
if (navLinks) {
    navLinks.querySelectorAll('.nav-item a').forEach(link => {
        link.addEventListener('click', closeMenu);
    });
}

const contactForm = document.querySelector('form[action="contact_handler.php"]');
if (contactForm) {
    contactForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(contactForm);
        await fetch(contactForm.action, { method: 'POST', body: formData });
        alert('Thanks for reaching out! Your message was received.');
        contactForm.reset();
    });
}

const orderForm = document.getElementById('orderForm');
if (orderForm) {
    orderForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const item = orderForm.order_item.value.trim();
        const amount = orderForm.order_amount.value.trim();
        const note = orderForm.order_note.value.trim();
        if (!item || !amount) return;
        const msg = `Order request:%0AItem: ${encodeURIComponent(item)}%0AAmount: ${encodeURIComponent(amount)}${note ? `%0ANote: ${encodeURIComponent(note)}` : ''}`;
        const waUrl = `https://wa.me/?text=${msg}`;
        window.open(waUrl, '_blank');
    });
}
