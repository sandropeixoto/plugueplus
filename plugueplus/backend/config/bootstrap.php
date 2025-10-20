<?php

$rootDir = dirname(__DIR__);

if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
}

$envFile = $rootDir . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}

date_default_timezone_set('UTC');

require_once __DIR__ . '/../config/database.php';

