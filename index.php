<?php
session_start();
$storeName = getenv('STORE_NAME') ?: 'Ranjana Didi ko Achar';
$csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16));
$_SESSION['csrf_token'] = $csrfToken;

require __DIR__ . '/components/header.php';
require __DIR__ . '/components/hero.php';
require __DIR__ . '/components/store.php';
require __DIR__ . '/components/order.php';
require __DIR__ . '/components/inventory.php';
require __DIR__ . '/components/features.php';
require __DIR__ . '/components/about.php';
require __DIR__ . '/components/contact.php';
require __DIR__ . '/components/footer.php';
require __DIR__ . '/components/scripts.php';
