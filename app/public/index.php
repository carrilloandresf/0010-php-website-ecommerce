<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Controllers/HomeController.php';

$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';

match ($path) {
    '/terminos' => include __DIR__ . '/terminos.php',
    '/vende'    => include __DIR__ . '/vende.php',
    default     => (new HomeController())->index(),
};
