<?php

/**
 * Router para PHP built-in server (php artisan serve).
 * Sirve archivos estáticos de public/ directamente;
 * el resto va a través de Laravel.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
