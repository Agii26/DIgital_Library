<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If file exists in public directory, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri) && is_file(__DIR__ . $uri)) {
    return false;
}

// Otherwise route through Laravel
require_once __DIR__ . '/index.php';