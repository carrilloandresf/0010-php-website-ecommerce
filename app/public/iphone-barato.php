<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/ProductModel.php';

$allProds = (new ProductModel())->getProducts();
$products = array_values(array_filter($allProds, fn(array $p): bool => $p['brand'] === 'Apple'));

$siteUrl = 'https://fromusa.com.co';
$pageUrl = $siteUrl . '/iphone-barato-colombia';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => $siteUrl],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'iPhone barato Colombia', 'item' => $pageUrl],
            ],
        ],
        [
            '@type'           => 'ItemList',
            'name'            => 'iPhone y Apple importados de USA — precios baratos en Colombia',
            'url'             => $pageUrl,
            'numberOfItems'   => count($products),
            'itemListElement' => array_map(static function (array $p, int $i) use ($siteUrl): array {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'item'     => [
                        '@type'       => 'Product',
                        'name'        => $p['name'],
                        'brand'       => ['@type' => 'Brand', 'name' => 'Apple'],
                        'description' => $p['description'] ?: $p['name'],
                        'offers'      => [
                            '@type'         => 'Offer',
                            'priceCurrency' => 'COP',
                            'price'         => $p['price'],
                            'availability'  => 'https://schema.org/InStock',
                            'url'           => $siteUrl,
                            'seller'        => ['@type' => 'Organization', 'name' => 'FromUSA.com.co'],
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

  <title>iPhone Barato en Colombia — Importados de USA | FromUSA.com.co</title>
  <meta name="description" content="Encuentra iPhone barato en Colombia, importado directo de USA. iPhone 16 Pro Max, AirPods, iPad y más Apple originales — precios en COP, vendedores certificados, contacto por WhatsApp.">
  <meta name="keywords" content="iphone barato colombia, iphone barato, iphone económico colombia, comprar iphone de usa, iphone importado colombia, iphone 16 pro max barato, apple barato colombia, donde comprar iphone barato colombia">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= $pageUrl ?>">
  <meta name="author" content="FromUSA.com.co">
  <link rel="icon" type="image/png" href="/bandera.png">
  <link rel="apple-touch-icon" href="/bandera.png">

  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= $pageUrl ?>">
  <meta property="og:site_name"   content="FromUSA.com.co">
  <meta property="og:locale"      content="es_CO">
  <meta property="og:title"       content="iPhone Barato en Colombia — Importados de USA | FromUSA.com.co">
  <meta property="og:description" content="iPhone barato importado de USA en Colombia. Precios reales en COP, originales, vendedores certificados. Contacto directo por WhatsApp. Sin comisiones.">

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
      <a href="/categoria/apple" class="hover:text-navy transition">Apple</a>
      <span class="mx-1.5">›</span>
      <span class="text-navy font-medium">iPhone Barato Colombia</span>
    </nav>

    <!-- Hero H1 -->
    <div class="mb-8">
      <h1 class="font-bold text-2xl md:text-3xl text-navy leading-tight mb-2">
        iPhone Barato importado de USA en Colombia
      </h1>
      <p class="text-gray-500 text-sm">
        Todos los productos Apple disponibles — iPhone, AirPods, iPad, Apple Watch y más.<br>
        Precios reales en COP · Originales · Contacto directo por WhatsApp · Sin comisiones
      </p>
    </div>

    <!-- Productos -->
    <?php if (empty($products)): ?>
    <div class="text-center py-16 text-gray-400">
      <p class="text-lg font-semibold mb-2">No hay productos Apple disponibles ahora.</p>
      <a href="/" class="text-usared hover:underline text-sm">Ver todo el catálogo →</a>
    </div>
    <?php else: ?>

    <p class="text-xs text-gray-400 mb-4 font-medium"><?= count($products) ?> productos Apple disponibles</p>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-10">
      <?php foreach ($products as $p):
        $hasImg    = !empty($p['images']);
        $discount  = $p['discount'];
        $showBadge = $discount >= 10;
      ?>
      <a href="/" class="block bg-white rounded-2xl p-3.5 shadow-[0_2px_16px_rgba(0,0,0,0.07)] hover:shadow-lg transition relative group">

        <div class="absolute top-2.5 left-2.5 z-10">
          <span class="bg-navy text-white text-[10px] font-bold px-2 py-0.5 rounded-full tracking-wide">Apple</span>
        </div>

        <?php if ($showBadge): ?>
        <div class="absolute top-2.5 right-2.5 z-10">
          <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">−<?= $discount ?>%</span>
        </div>
        <?php endif; ?>

        <div class="w-full aspect-square rounded-xl overflow-hidden mb-3 bg-white flex items-center justify-center">
          <?php if ($hasImg): ?>
          <img src="<?= htmlspecialchars($p['images'][0]) ?>"
               alt="<?= htmlspecialchars($p['name']) ?> precio Colombia"
               class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
               loading="lazy">
          <?php else: ?>
          <span class="text-4xl opacity-30">🍎</span>
          <?php endif; ?>
        </div>

        <h2 class="font-semibold text-xs leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h2>

        <?php if ($p['market_price'] !== null && $p['market_price'] > $p['price']): ?>
        <p class="text-gray-400 text-[11px] line-through leading-none mb-0.5">
          $<?= number_format($p['market_price'], 0, ',', '.') ?>
        </p>
        <?php endif; ?>

        <p class="text-usared font-bold text-base">$<?= number_format($p['price'], 0, ',', '.') ?></p>

        <?php if ($p['capacity']): ?>
        <p class="text-gray-400 text-[10px] mt-0.5"><?= htmlspecialchars($p['capacity']) ?></p>
        <?php endif; ?>

        <div class="mt-2.5 w-full bg-navy text-white text-xs font-semibold py-2 rounded-xl text-center group-hover:bg-usablue transition">
          Ver en el catálogo
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- Contenido SEO -->
    <section class="bg-white rounded-2xl px-6 py-7 mb-8 shadow-[0_2px_16px_rgba(0,0,0,0.05)]">
      <h2 class="font-bold text-lg text-navy mb-3">¿Por qué son más baratos los iPhone importados de USA?</h2>
      <div class="text-sm text-gray-600 leading-relaxed space-y-3">
        <p>
          En Estados Unidos los iPhone tienen un precio significativamente menor al precio oficial que Apple establece para Colombia. Esto se debe al tipo de cambio, los impuestos de importación y los márgenes que agregan los distribuidores locales.
        </p>
        <p>
          En <strong>FromUSA.com.co</strong> conectamos directamente a personas que traen iPhone y productos Apple desde USA con compradores colombianos que los buscan. Al eliminar los intermediarios, los precios son mucho más competitivos que en tiendas de cadena o distribuidores oficiales.
        </p>
        <p>
          Todos los vendedores en nuestra plataforma son verificados. Los productos son <strong>originales</strong> — no réplicas, no clones. Cada trato se hace directamente por WhatsApp entre comprador y vendedor, sin comisiones ni cargos adicionales de la plataforma.
        </p>
      </div>
    </section>

    <section class="bg-white rounded-2xl px-6 py-7 mb-8 shadow-[0_2px_16px_rgba(0,0,0,0.05)]">
      <h2 class="font-bold text-lg text-navy mb-3">¿Cómo comprar un iPhone barato en Colombia?</h2>
      <ol class="text-sm text-gray-600 leading-relaxed space-y-2 list-decimal list-inside">
        <li>Elige el iPhone o producto Apple que te interesa en la lista de arriba.</li>
        <li>Haz clic en el producto para ver las fotos y detalles completos.</li>
        <li>Contáctanos directamente por WhatsApp para consultar disponibilidad, condición y coordinar el pago y la entrega.</li>
        <li>Negociación directa: tú hablas con el vendedor, sin intermediarios.</li>
      </ol>
    </section>

    <!-- CTA WhatsApp -->
    <div class="bg-navy rounded-2xl px-6 py-7 text-white text-center">
      <h2 class="font-bold text-lg mb-1">¿No encontraste el iPhone que buscas?</h2>
      <p class="text-white/60 text-sm mb-4">Escríbenos y te avisamos cuando tengamos disponible el modelo que necesitas.</p>
      <a href="https://wa.me/17865683345?text=Hola%2C%20estoy%20buscando%20un%20iPhone%20barato%20en%20Colombia.%20%C2%BFQu%C3%A9%20modelos%20tienen%20disponibles%3F"
         target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
        </svg>
        Consultar por WhatsApp
      </a>
    </div>

  </main>

  <?php include __DIR__ . '/_footer.php'; ?>

</body>
</html>
