<?php
session_start();
header('Content-Type: application/json');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$token = $_POST['csrf_token'] ?? '';

if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request token.']);
    exit;
}

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid name, email, and message.']);
    exit;
}

echo json_encode([
    'status' => 'ok',
    'received' => [
        'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    ],
]);
