<?php

declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$app = new \Sitemon\App();

require_once __DIR__ . '/../config/routes.php';

return $app;
