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
navOverlay.className = 'nav-overlay';
document.body.appendChild(navOverlay);

function toggleMenu() {
    mobileNavToggle.classList.toggle('active');
    navLinks.classList.toggle('active');
    navOverlay.classList.toggle('active');
}

function closeMenu() {
    mobileNavToggle.classList.remove('active');
    navLinks.classList.remove('active');
    navOverlay.classList.remove('active');
}

mobileNavToggle.addEventListener('click', toggleMenu);
navOverlay.addEventListener('click', closeMenu);
navLinks.querySelectorAll('.nav-item a').forEach(link => {
    link.addEventListener('click', closeMenu);
});

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
