<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Controllers/HomeController.php';

(new HomeController())->index();
