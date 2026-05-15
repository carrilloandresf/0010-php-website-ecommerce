<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/ProductModel.php';

$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';
$slug = substr($path, strlen('/categoria/'));

$categoryMeta = [
    'celulares' => [
        'label'       => 'Celulares',
        'h1'          => 'Celulares importados de USA en Colombia',
        'subtitle'    => 'iPhone, Samsung, Motorola y más — precios reales en COP, contacto directo por WhatsApp',
        'title'       => 'Celulares Importados de USA en Colombia | iPhone, Samsung y más — FromUSA.com.co',
        'description' => 'Compra celulares importados de USA en Colombia: iPhone barato, Samsung, Motorola y más. Precios reales en COP, vendedores certificados, contacto por WhatsApp. Sin comisiones.',
        'keywords'    => 'celulares importados usa colombia, iphone barato colombia, samsung barato colombia, celulares baratos importados, comprar celular de usa, telefono importado colombia',
    ],
    'apple' => [
        'label'       => 'Apple',
        'h1'          => 'Productos Apple importados de USA en Colombia',
        'subtitle'    => 'iPhone, iPad, AirPods, Apple Watch y más — originales, importados directo de USA',
        'title'       => 'Productos Apple Importados de USA en Colombia | iPhone Barato — FromUSA.com.co',
        'description' => 'Compra productos Apple importados de USA en Colombia: iPhone barato, iPad, AirPods, Apple Watch y más. Originales, precios en COP, vendedores certificados. Sin comisiones.',
        'keywords'    => 'iphone barato colombia, apple importado colombia, iphone económico, comprar iphone de usa, airpods baratos colombia, ipad importado colombia',
    ],
    'audifonos' => [
        'label'       => 'Audífonos',
        'h1'          => 'Audífonos importados de USA en Colombia',
        'subtitle'    => 'JBL, Apple AirPods, Samsung Galaxy Buds y más — originales, precios reales en COP',
        'title'       => 'Audífonos Importados de USA en Colombia | JBL, AirPods y más — FromUSA.com.co',
        'description' => 'Audífonos importados de USA en Colombia: JBL, AirPods, Samsung Galaxy Buds, Skullcandy y más. Originales, precios en COP, contacto por WhatsApp. Sin comisiones.',
        'keywords'    => 'audífonos importados colombia, airpods baratos colombia, jbl colombia, audífonos baratos originales, comprar audífonos de usa',
    ],
    'samsung' => [
        'label'       => 'Samsung',
        'h1'          => 'Samsung importados de USA en Colombia',
        'subtitle'    => 'Galaxy S, Galaxy A, Galaxy Tab y más — precios reales, importados directo de USA',
        'title'       => 'Samsung Importados de USA en Colombia | Galaxy Barato — FromUSA.com.co',
        'description' => 'Compra Samsung importados de USA en Colombia: Galaxy S22, Galaxy A36, Galaxy Tab y más. Precios en COP, vendedores certificados, contacto por WhatsApp. Sin comisiones.',
        'keywords'    => 'samsung barato colombia, samsung importado colombia, galaxy s colombia, galaxy a colombia, comprar samsung de usa',
    ],
    'tablets' => [
        'label'       => 'Tablets',
        'h1'          => 'Tablets importadas de USA en Colombia',
        'subtitle'    => 'iPad, Samsung Galaxy Tab y más — originales, precios reales en COP',
        'title'       => 'Tablets Importadas de USA en Colombia | iPad, Samsung — FromUSA.com.co',
        'description' => 'Tablets importadas de USA: iPad Air, Samsung Galaxy Tab y más. Precios en COP, contacto directo por WhatsApp. Sin comisiones. FromUSA.com.co',
        'keywords'    => 'tablets importadas colombia, ipad barato colombia, samsung galaxy tab colombia, tablet de usa',
    ],
    'consolas' => [
        'label'       => 'Consolas',
        'h1'          => 'Consolas de videojuegos importadas de USA en Colombia',
        'subtitle'    => 'Nintendo Switch y más — importadas directo de USA, precios en COP',
        'title'       => 'Consolas de Videojuegos Importadas de USA en Colombia — FromUSA.com.co',
        'description' => 'Consolas de videojuegos importadas de USA: Nintendo Switch y más. Precios en COP, contacto por WhatsApp. Sin comisiones. FromUSA.com.co',
        'keywords'    => 'consolas importadas colombia, nintendo switch colombia, consola de usa, videojuegos baratos colombia',
    ],
];

if (!array_key_exists($slug, $categoryMeta)) {
    header('HTTP/1.1 404 Not Found');
    include __DIR__ . '/404.php';
    exit;
}

$meta     = $categoryMeta[$slug];
$allProds = (new ProductModel())->getProducts();

// Filter products for this category, plus handle 'samsung' and 'apple' by brand
$products = array_filter($allProds, function (array $p) use ($slug): bool {
    if ($slug === 'samsung') {
        return $p['brand'] === 'Samsung';
    }
    if ($slug === 'apple') {
        return $p['brand'] === 'Apple';
    }
    return $p['category'] === $slug;
});
$products = array_values($products);

$siteUrl = 'https://fromusa.com.co';
$pageUrl = $siteUrl . '/categoria/' . $slug;

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => $siteUrl],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $meta['label'], 'item' => $pageUrl],
            ],
        ],
        [
            '@type'           => 'ItemList',
            'name'            => $meta['h1'],
            'url'             => $pageUrl,
            'numberOfItems'   => count($products),
            'itemListElement' => array_map(static function (array $p, int $i) use ($siteUrl): array {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'item'     => [
                        '@type'       => 'Product',
                        'name'        => $p['name'],
                        'brand'       => ['@type' => 'Brand', 'name' => $p['brand']],
                        'offers'      => [
                            '@type'         => 'Offer',
                            'priceCurrency' => 'COP',
                            'price'         => $p['price'],
                            'availability'  => 'https://schema.org/InStock',
                        ],
                    ],
                ];
                if (!empty($p['images'][0])) {
                    $item['item']['image'] = $siteUrl . $p['images'][0];
                }
                return $item;
            }, $products, array_keys($products)),
        ],
    ],
];
?>
<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z5M2GVB7L2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-Z5M2GVB7L2');
  </script>

  <title><?= htmlspecialchars($meta['title']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta['description']) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($meta['keywords']) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= $pageUrl ?>">
  <meta name="author" content="FromUSA.com.co">
  <link rel="icon" type="image/png" href="/bandera.png">
  <link rel="apple-touch-icon" href="/bandera.png">

  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= $pageUrl ?>">
  <meta property="og:site_name"   content="FromUSA.com.co">
  <meta property="og:locale"      content="es_CO">
  <meta property="og:title"       content="<?= htmlspecialchars($meta['title']) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($meta['description']) ?>">

  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdn.tailwindcss.com">
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&family=Rowdies:wght@400&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { navy:'#0A1628', usared:'#B22234', usablue:'#3C3B6E', usalight:'#F5F5F7' },
          fontFamily: { display:['"Bebas Neue"','sans-serif'], body:['"DM Sans"','sans-serif'], marketplace:['"Rowdies"','cursive'] }
        }
      }
    }
  </script>
</head>
<body class="bg-usalight font-body text-navy flex flex-col min-h-screen">

  <!-- ── Header ──────────────────────────────────────────────────── -->
  <header class="bg-navy text-white sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-5 py-3 flex items-center justify-between">
      <a href="/" class="flex flex-col leading-none select-none gap-1">
        <div class="flex items-center gap-2">
          <div class="flex items-baseline gap-1.5">
            <span class="text-white/60 text-[10px] font-medium tracking-[0.18em] uppercase">from</span>
            <span class="font-display text-[24px] tracking-wide leading-none" style="color:#E8253A">USA</span>
            <span class="text-white/50 text-[11px] font-medium">.com.co</span>
          </div>
          <span class="font-marketplace text-[8px] tracking-[0.2em] leading-none px-1.5 py-[4px] rounded-sm" style="color:#F0C040;border:1px solid rgba(240,192,64,0.4);background:rgba(240,192,64,0.08)">MARKETPLACE</span>
        </div>
        <span class="text-white/35 text-[8px] tracking-[0.2em] uppercase font-medium">De allá · Para acá · Sin rodeos</span>
      </a>
      <a href="/" class="text-white/70 hover:text-white text-xs transition">← Ver todo el catálogo</a>
    </div>
  </header>

  <!-- ── Main ─────────────────────────────────────────────────────── -->
  <main class="flex-1 max-w-5xl mx-auto w-full px-5 py-8">

    <!-- Breadcrumb -->
    <nav class="text-xs text-gray-400 mb-5">
      <a href="/" class="hover:text-navy transition">Inicio</a>
      <span class="mx-1.5">›</span>
      <span class="text-navy font-medium"><?= htmlspecialchars($meta['label']) ?></span>
    </nav>

    <!-- H1 + subtítulo -->
    <h1 class="font-bold text-2xl md:text-3xl text-navy leading-tight mb-2">
      <?= htmlspecialchars($meta['h1']) ?>
    </h1>
    <p class="text-gray-500 text-sm mb-6"><?= htmlspecialchars($meta['subtitle']) ?></p>

    <?php if (empty($products)): ?>
    <div class="text-center py-16 text-gray-400">
      <p class="text-lg font-semibold mb-2">No hay productos disponibles en esta categoría ahora.</p>
      <a href="/" class="text-usared hover:underline text-sm">Ver todo el catálogo →</a>
    </div>
    <?php else: ?>

    <!-- Conteo -->
    <p class="text-xs text-gray-400 mb-4 font-medium"><?= count($products) ?> productos disponibles</p>

    <!-- Grid de productos -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
      <?php foreach ($products as $p):
        $hasImg   = !empty($p['images']);
        $discount = $p['discount'];
        $showBadge = $discount >= 10;
      ?>
      <a href="/?producto=<?= $p['id'] ?>" class="block bg-white rounded-2xl p-3.5 shadow-[0_2px_16px_rgba(0,0,0,0.07)] hover:shadow-lg transition relative group">

        <?php if ($p['brand'] === 'Apple'): ?>
        <div class="absolute top-2.5 left-2.5 z-10">
          <span class="bg-navy text-white text-[10px] font-bold px-2 py-0.5 rounded-full tracking-wide">Apple</span>
        </div>
        <?php endif; ?>

        <?php if ($showBadge): ?>
        <div class="absolute top-2.5 right-2.5 z-10">
          <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">−<?= $discount ?>%</span>
        </div>
        <?php endif; ?>

        <div class="w-full aspect-square rounded-xl overflow-hidden mb-3 bg-white flex items-center justify-center">
          <?php if ($hasImg): ?>
          <img src="<?= htmlspecialchars($p['images'][0]) ?>"
               alt="<?= htmlspecialchars($p['name']) ?>"
               class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
               loading="lazy">
          <?php else: ?>
          <span class="text-4xl opacity-30">📦</span>
          <?php endif; ?>
        </div>

        <h2 class="font-semibold text-xs leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h2>

        <?php if ($p['market_price'] !== null && $p['market_price'] > $p['price']): ?>
        <p class="text-gray-400 text-[11px] line-through leading-none mb-0.5">
          $<?= number_format($p['market_price'], 0, ',', '.') ?>
        </p>
        <?php endif; ?>

        <p class="text-usared font-bold text-base">$<?= number_format($p['price'], 0, ',', '.') ?></p>

        <p class="text-gray-400 text-[10px] mt-1"><?= htmlspecialchars($p['brand']) ?></p>

        <div class="mt-2.5 w-full bg-navy text-white text-xs font-semibold py-2 rounded-xl text-center group-hover:bg-usablue transition">
          Ver producto
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- CTA WhatsApp -->
    <div class="mt-10 bg-navy rounded-2xl px-6 py-6 text-white text-center">
      <h2 class="font-bold text-lg mb-1">¿Buscas algo específico?</h2>
      <p class="text-white/60 text-sm mb-4">Escríbenos por WhatsApp y te ayudamos a encontrar el producto que necesitas.</p>
      <a href="https://wa.me/17865683345?text=Hola%2C%20estoy%20buscando%20<?= urlencode($meta['label']) ?>%20importados%20de%20USA"
         target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
        </svg>
        Consultar disponibilidad por WhatsApp
      </a>
    </div>

    <?php endif; ?>

  </main>

  <?php include __DIR__ . '/_footer.php'; ?>

</body>
</html>
