
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_for_homepage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Inventory Management System</title>
</head>

<body>
    <!-- Header Section -->
    <div class="nav-container">
        <header class="section-shell">
            <div class="logo">
                <div class="pill">Single vendor</div>
                <h1>Ranjana Didi ko Achar</h1>
            </div>
            <button class="mobile-nav-toggle">
                <span class="hamburger"></span>
            </button>
            <nav>
                <ul class="nav-links">
                    <li class="nav-item">
                        <a href="#home" class="btn-home">Home</a>
                    </li>

                    <li class="nav-item">
                        <a href="#store" class="btn-features">Storefront</a>
                    </li>

                    <li class="nav-item">
                        <a href="#inventory" class="btn-about">Inventory</a>
                    </li>

                    <li class="nav-item">
                        <a href="#features" class="btn-features">Features</a>
                    </li>

                    <li class="nav-item">
                        <a href="#about" class="btn-about">About</a>
                    </li>

                    <li class="nav-item">
                        <a href="#contact" class="btn-contact">Contact</a>
                    </li>

                    <li class="nav-item">
                        <a href="login/login.php" class="btn-login">Login</a>
                    </li>
                </ul>
            </nav>
        </header>
    </div>

    <!-- Hero Section -->
    <section class="hero section-shell" id="home">
        <div class="hero-content surface">
            <div class="pill">Cozy single-vendor storefront</div>
            <h2>Fresh achar, one vendor, one tidy inventory.</h2>
            <p>Give Ranjana Didi ko Achar a beautiful, unified home. Manage stock, purchases, and sales from the same friendly dashboard—ready for MariaDB or SQLite.</p>
            <div class="cta-buttons">
                <a href="login/login.php" class="btn">Get Started</a>
                <a href="#store" class="btn btn-ghost">Browse Storefront</a>
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

    <section class="storefront section-shell" id="store">
        <div class="section-heading">
            <div class="pill">Shop</div>
            <h2>Ranjana’s best-selling achar jars</h2>
            <p class="muted">Highlight a single vendor’s catalog with consistent product cards, quick details, and add-to-cart ready actions.</p>
        </div>
        <div class="feature-cards">
            <div class="card store-card">
                <div class="badge">Mild</div>
                <h3>Lapsi Achar</h3>
                <p>Sweet & tangy with hand-ground spices. Perfect for snack time.</p>
                <div class="store-meta">
                    <span>Stock synced</span>
                    <span class="dot"></span>
                    <span>Ready to ship</span>
                </div>
            </div>
            <div class="card store-card">
                <div class="badge badge-accent">Signature</div>
                <h3>Titaura Mix</h3>
                <p>Colorful mix for the real fans of homemade flavors.</p>
                <div class="store-meta">
                    <span>Batch tracked</span>
                    <span class="dot"></span>
                    <span>Single vendor</span>
                </div>
            </div>
            <div class="card store-card">
                <div class="badge">Spicy</div>
                <h3>Chilli Garlic</h3>
                <p>Cozy heat with roasted garlic—pairs with anything crispy.</p>
                <div class="store-meta">
                    <span>Fresh jars</span>
                    <span class="dot"></span>
                    <span>Low on waste</span>
                </div>
            </div>
        </div>
    </section>

    <section class="inventory section-shell surface" id="inventory">
        <div class="inventory-grid">
            <div class="inventory-copy">
                <div class="section-heading">
                    <div class="pill">Inventory system</div>
                    <h2>Built for simple operations with DRY components</h2>
                    <p class="muted">Use the same components to approve purchases, track sales, and monitor stock. One structure for every screen.</p>
                </div>
                <ul class="inventory-list">
                    <li><span class="dot"></span>Reusable cards and grids keep the UI consistent.</li>
                    <li><span class="dot"></span>Switch your database: MariaDB/MySQL or SQLite via one config.</li>
                    <li><span class="dot"></span>Cozy white layout that stays focused on data.</li>
                </ul>
                <div class="cta-buttons">
                    <a href="login/login.php" class="btn">Open dashboard</a>
                </div>
            </div>
            <div class="inventory-cards">
                <div class="card stat-card">
                    <p class="eyebrow">Vendors</p>
                    <h3>Single vendor ready</h3>
                    <p>Keep one catalog and reduce clutter.</p>
                </div>
                <div class="card stat-card">
                    <p class="eyebrow">Storage</p>
                    <h3>MariaDB / SQLite</h3>
                    <p>Local SQLite for quick demos, MariaDB for production scale.</p>
                </div>
                <div class="card stat-card">
                    <p class="eyebrow">UI</p>
                    <h3>Shared components</h3>
                    <p>Buttons, cards, and grids reuse the same theme.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2>Key Features</h2>
        <div class="feature-cards">
            <div class="card">
                <img src="workflow.png" alt="Workflow Icon">
                <h3>Purchase Approval Workflow</h3>
                <p>Efficiently manage and approve purchase requests with ease.</p>
            </div>
            <div class="card">
                <img src="analytics.png" alt="Analytics Icon">
                <h3>Detailed Analytics</h3>
                <p>Gain insights into sales, purchases, and inventory trends with in-depth analytics.</p>
            </div>
            <div class="card">
                <img src="tracking.png" alt="Tracking Icon">
                <h3>Real-Time Tracking</h3>
                <p>Track inventory and purchases in real time.</p>
            </div>
            <div class="card">
                <img src="reporting.png" alt="Reporting Icon">
                <h3>Custom Reports</h3>
                <p>Create and generate custom reports to analyze inventory performance and trends.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <h2>About Us</h2>
        <p>Our Inventory Management System is designed to simplify workflows for small businesses and inventory managers. Manage inventory, track purchases, and improve efficiency—all in one place.</p>
    </section>

    <!-- Add a Contact Section -->
    <section class="contact" id="contact">
        <h2>Contact Us</h2>
        <div class="contact-content">       
            <form action="process_form.php" method="POST">
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

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
        <div class="contact-info">
            <p> <a href="mailto:trackify@gmail.com">trackify@gmail.com</a></p>
            <p> +123 456 7890</p>
        </div>
            <ul class="footer-links">
                <li>&copy; 2025 trackify</li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms & Conditions</a></li>
            </ul>
        </div>
        <div class="social-icons">
            <a href="https://www.facebook.com/" alt="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://x.com/" alt="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="https://np.linkedin.com/" alt="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
    </footer>
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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
    </script>
    <script>
        // Select elements
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        // Create and append overlay element to the body
        const navOverlay = document.createElement('div');
        navOverlay.className = 'nav-overlay';
        document.body.appendChild(navOverlay);

        // Toggle mobile menu and overlay visibility
        function toggleMenu() {
            mobileNavToggle.classList.toggle('active'); // Toggle hamburger icon animation
            navLinks.classList.toggle('active'); // Show/hide nav links
            navOverlay.classList.toggle('active'); // Show/hide overlay
        }

        // Close the menu
        function closeMenu() {
            mobileNavToggle.classList.remove('active');
            navLinks.classList.remove('active');
            navOverlay.classList.remove('active');
        }

        // Event listener for the mobile menu toggle button
        mobileNavToggle.addEventListener('click', () => {
            toggleMenu();
        });

        // Event listener for the overlay (clicking outside the menu)
        navOverlay.addEventListener('click', () => {
            closeMenu();
        });

        // Optional: Close the menu when a nav link is clicked (smooth navigation experience)
        navLinks.querySelectorAll('.nav-item a').forEach(link => {
            link.addEventListener('click', () => {
                closeMenu();
            });
        });
        
    </script>

    
</body>

</html>
