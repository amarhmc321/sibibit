<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Membuat token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Mengecek apakah token valid
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) return false;
    $isValid = hash_equals($_SESSION['csrf_token'], $token);
    if ($isValid) {
        // opsional: hapus token biar sekali pakai
        unset($_SESSION['csrf_token']);
    }
    return $isValid;
}
