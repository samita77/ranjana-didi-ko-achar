<?php
$navLinks = [
    ['label' => 'Home', 'href' => '#home'],
    ['label' => 'Storefront', 'href' => '#store'],
    ['label' => 'Inventory', 'href' => '#inventory'],
    ['label' => 'Features', 'href' => '#features'],
    ['label' => 'About', 'href' => '#about'],
    ['label' => 'Contact', 'href' => '#contact'],
    ['label' => 'Login', 'href' => 'login/login.php', 'class' => 'btn-login'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_for_homepage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title><?php echo htmlspecialchars($storeName); ?></title>
</head>
<body>
    <div class="nav-container">
        <header class="section-shell">
            <div class="logo">
                <div class="pill">Single vendor</div>
                <h1><?php echo htmlspecialchars($storeName); ?></h1>
            </div>
            <button class="mobile-nav-toggle">
                <span class="hamburger"></span>
            </button>
            <nav>
                <ul class="nav-links">
                    <?php foreach ($navLinks as $link): ?>
                        <li class="nav-item">
                            <a href="<?php echo $link['href']; ?>" class="<?php echo $link['class'] ?? ''; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>
    </div>
