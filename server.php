<?php

/**
 * Router para PHP built-in server.
 * Doc root = public/ (pasado via -t flag en el CMD).
 * return false → PHP sirve el archivo estático desde doc root (public/).
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Si el archivo existe en public/ → servir estáticamente
if ($uri !== '/' && file_exists($_SERVER['DOCUMENT_ROOT'] . $uri)) {
    return false;
}

// Todo lo demás → Laravel
require_once $_SERVER['DOCUMENT_ROOT'] . '/index.php';
