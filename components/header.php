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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title><?php echo htmlspecialchars($storeName); ?></title>
</head>
<body class="bg-gray-50 text-slate-900">
    <div class="sticky top-0 bg-white shadow z-50">
        <header class="max-w-6xl mx-auto flex items-center justify-between px-6 py-4">
            <div class="flex flex-col gap-1">
                <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Single vendor</div>
                <h1 class="text-2xl font-semibold"><?php echo htmlspecialchars($storeName); ?></h1>
            </div>
            <button class="mobile-nav-toggle md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 text-slate-700">
                <span class="hamburger bg-slate-800 block w-6 h-0.5 relative">
                    <span class="absolute -top-2 left-0 w-6 h-0.5 bg-slate-800"></span>
                    <span class="absolute top-2 left-0 w-6 h-0.5 bg-slate-800"></span>
                </span>
            </button>
            <nav>
                <ul class="nav-links hidden md:flex items-center gap-3">
                    <?php foreach ($navLinks as $link): ?>
                        <li class="nav-item">
                            <a href="<?php echo $link['href']; ?>" class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 <?php echo $link['class'] ?? ''; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>
    </div>
