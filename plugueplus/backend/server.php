<?php

$publicDir = __DIR__ . '/public';
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
$filePath = realpath($publicDir . $uri);

if ($filePath && str_starts_with($filePath, $publicDir) && is_file($filePath)) {
    return false;
}

require $publicDir . '/index.php';
