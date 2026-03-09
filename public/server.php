<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$filePath = __DIR__ . $uri;

error_log("URI: $uri | Looking for: $filePath | Exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    return false;
}

require_once __DIR__ . '/index.php';