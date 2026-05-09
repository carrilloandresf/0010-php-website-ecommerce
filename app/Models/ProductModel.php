<?php

class ProductModel
{
    private string $dataPath;
    private string $imgDir;

    private array $categoryMap = [
        'Teléfono'              => 'celulares',
        'Audífonos'             => 'audifonos',
        'Tablet'                => 'tablets',
        'Localizador'           => 'apple',
        'Parlante Bluetooth'    => 'parlantes',
        'Power bank'            => 'cargadores',
        'Consola de videojuegos'=> 'consolas',
        'Timbre con cámara'     => 'camaras',
        'Cámara exterior'       => 'camaras',
        'Cámara interior'       => 'camaras',
        'Lámpara solar'         => 'iluminacion',
        'Luz LED con sensor'    => 'iluminacion',
        'Gafas de sol'          => 'gafas',
    ];

    public function __construct()
    {
        $base = dirname(__DIR__, 2);
        $this->dataPath = $base . '/data/inventory.json';
        $this->imgDir   = $base . '/img/';
    }

    public function getProducts(): array
    {
        $raw  = file_get_contents($this->dataPath);
        $data = json_decode($raw, true);

        $products = [];

        foreach ($data['orden_productos'] as $key) {
            $p = $data['productos'][$key];

            if ($p['cantidad'] <= 0 || $p['precio_venta_cop'] === null) {
                continue;
            }

            $price      = (int) $p['precio_venta_cop'];
            $marketPrice = isset($p['precio_mercado_cop']) ? (int) $p['precio_mercado_cop'] : null;
            $discount   = $this->calcDiscount($price, $marketPrice);

            $products[] = [
                'id'           => (int) $p['indice'],
                'slug'         => $p['slug'],
                'name'         => $p['nombre_web'],
                'model'        => $p['modelo']     ?? null,
                'price'        => $price,
                'market_price' => $marketPrice,
                'discount'     => $discount,
                'category'     => $this->mapCategory($p['categoria'] ?? null),
                'brand'        => $p['marca'] ?? '',
                'description'  => $p['descripcion'] ?? '',
                'capacity'     => $p['capacidad'] ?? '',
                'stock'        => (int) ($p['cantidad'] ?? 0),
                'price_usd'    => $p['precio_usd'] ?? null,
                'images'       => $this->getProductImages((int) $p['indice']),
            ];
        }

        return $products;
    }

    private function getProductImages(int $indice): array
    {
        // Match separators: underscore, dot, dash
        $files = glob($this->imgDir . $indice . '[_.-]*');

        if (empty($files)) {
            return [];
        }

        // Sort by sequence number extracted after the separator (ignores separator char)
        usort($files, function (string $a, string $b) use ($indice): int {
            return $this->extractSequence($a, $indice) <=> $this->extractSequence($b, $indice);
        });

        return array_values(array_map(
            static fn(string $f): string => '/img/' . basename($f),
            $files
        ));
    }

    private function extractSequence(string $filepath, int $indice): int
    {
        $basename = basename($filepath);
        if (preg_match('/^' . $indice . '[_.\-](\d+)/', $basename, $m)) {
            return (int) $m[1];
        }
        return 999;
    }

    private function mapCategory(?string $categoria): string
    {
        if ($categoria === null) {
            return 'otros';
        }
        return $this->categoryMap[$categoria] ?? 'otros';
    }

    private function calcDiscount(int $price, ?int $marketPrice): int
    {
        if ($marketPrice === null || $marketPrice <= $price) {
            return 0;
        }
        return (int) round(($marketPrice - $price) / $marketPrice * 100);
    }
}
