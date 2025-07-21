<?php

// Load default settings
$settings = require __DIR__ . '/settings.default.php';

// Overwrite default settings with environment specific local settings
$env = getenv('APP_ENV');
$environmentFile = __DIR__ . strtolower(sprintf('/settings.%s.php', $env));

if (file_exists($environmentFile)) {
    require $environmentFile;
}

return $settings;
