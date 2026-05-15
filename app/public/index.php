<?php

declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Controllers/HomeController.php';

$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';

if (str_starts_with($path, '/ciudad/')) {
    include __DIR__ . '/ciudad.php';
    return;
}

if (str_starts_with($path, '/categoria/')) {
    include __DIR__ . '/categoria.php';
    return;
}

match ($path) {
    '/terminos'               => include __DIR__ . '/terminos.php',
    '/vende'                  => include __DIR__ . '/vende.php',
    '/marketplace'            => include __DIR__ . '/marketplace.php',
    '/como-comprar'           => include __DIR__ . '/como-comprar.php',
    '/faq'                    => include __DIR__ . '/faq.php',
    '/iphone-barato-colombia' => include __DIR__ . '/iphone-barato.php',
    default                   => (new HomeController())->index(),
};
