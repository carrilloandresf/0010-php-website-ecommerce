<?php

class HomeController
{
    private const ALL_CATEGORIES = [
        'all'       => ['id' => 'all',       'name' => 'Todo',       'emoji' => '🔥'],
        'celulares' => ['id' => 'celulares', 'name' => 'Celulares',  'emoji' => '📱'],
        'audifonos' => ['id' => 'audifonos', 'name' => 'Audífonos',  'emoji' => '🎧'],
        'tablets'   => ['id' => 'tablets',   'name' => 'Tablets',    'emoji' => '📲'],
        'consolas'  => ['id' => 'consolas',  'name' => 'Consolas',   'emoji' => '🎮'],
        'parlantes' => ['id' => 'parlantes', 'name' => 'Parlantes',  'emoji' => '🔊'],
        'cargadores'=> ['id' => 'cargadores','name' => 'Cargadores', 'emoji' => '🔋'],
        'camaras'   => ['id' => 'camaras',   'name' => 'Cámaras',   'emoji' => '📷'],
        'iluminacion'=>['id' => 'iluminacion','name' => 'Iluminación','emoji' => '💡'],
        'gafas'     => ['id' => 'gafas',     'name' => 'Gafas',      'emoji' => '🕶️'],
        'apple'     => ['id' => 'apple',     'name' => 'Apple',      'emoji' => '🍎'],
        'otros'     => ['id' => 'otros',     'name' => 'Otros',      'emoji' => '📦'],
    ];

    public function index(): void
    {
        $model    = new ProductModel();
        $products = $model->getProducts();
        $categories = $this->buildCategories($products);

        include __DIR__ . '/../Views/home.php';
    }

    private function buildCategories(array $products): array
    {
        $usedSlugs = array_unique(array_column($products, 'category'));

        $categories = [self::ALL_CATEGORIES['all']];

        foreach (self::ALL_CATEGORIES as $slug => $cat) {
            if ($slug !== 'all' && in_array($slug, $usedSlugs, true)) {
                $categories[] = $cat;
            }
        }

        return $categories;
    }
}
