<!doctype html>
<html lang="es" prefix="og: https://ogp.me/ns#" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ── SEO primario ──────────────────────────────────────────────────── -->
  <title>FromUSA.com.co — Marketplace de importados USA en Colombia</title>
  <meta name="description" content="La plaza de comercio online más grande de Colombia para productos importados de USA. Celulares, Apple, tablets, consolas, audífonos y más. Vendedores reales, precios en COP, contacto por WhatsApp. Sin comisiones.">
  <meta name="keywords" content="marketplace importados colombia, productos importados usa colombia, comprar importados colombia, plaza comercio colombia, mercado importados usa, tecnología usa colombia, celulares importados colombia, iphone colombia, samsung colombia, fromusa, importados directo usa">
  <meta name="author" content="FromUSA.com.co">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="googlebot" content="index, follow">
  <link rel="canonical" href="https://fromusa.com.co/">

  <!-- ── Open Graph (Facebook, LinkedIn, WhatsApp, Telegram) ────────────── -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="https://fromusa.com.co/">
  <meta property="og:site_name"   content="FromUSA.com.co">
  <meta property="og:locale"      content="es_CO">
  <meta property="og:title"       content="FromUSA.com.co — Marketplace de importados USA en Colombia">
  <meta property="og:description" content="La plaza de comercio online más grande de Colombia para productos importados de USA. Conectamos compradores y vendedores. Precios en COP, trato directo por WhatsApp.">
  <meta property="og:image"       content="https://fromusa.com.co/og-image.php">
  <meta property="og:image:secure_url" content="https://fromusa.com.co/og-image.php">
  <meta property="og:image:type"  content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt"   content="FromUSA.com.co — Tecnología importada desde USA">

  <!-- ── Twitter / X Card ─────────────────────────────────────────────── -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="FromUSA.com.co — Marketplace de importados USA en Colombia">
  <meta name="twitter:description" content="Marketplace colombiano de importados USA. Celulares, Apple, consolas, audífonos y más. Contacto directo por WhatsApp. Sin comisiones.">
  <meta name="twitter:image"       content="https://fromusa.com.co/og-image.php">
  <meta name="twitter:image:alt"   content="FromUSA.com.co">

  <!-- ── PWA / Dispositivos móviles ────────────────────────────────────── -->
  <link rel="manifest" href="/manifest.json">
  <link rel="icon" type="image/png" href="/bandera.png">
  <meta name="theme-color" content="#0A1628">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="FromUSA">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="apple-touch-icon" href="/bandera.png">

  <!-- ── Datos estructurados JSON-LD (Google, Bing, IAs) ───────────────── -->
<?php
$siteUrl = 'https://fromusa.com.co';
$topProducts = array_slice($products, 0, 20);
$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Organization',
            '@id'         => $siteUrl . '/#organization',
            'name'        => 'FromUSA.com.co',
            'url'         => $siteUrl,
            'logo'        => $siteUrl . '/og-image.php',
            'description' => 'Plataforma tecnológica intermediaria que conecta compradores y vendedores de productos importados desde Estados Unidos en Colombia. Desarrollada por 111labs S.A.S.',
            'founder'     => ['@type' => 'Organization', 'name' => '111labs S.A.S', 'url' => 'https://111labs.net'],
            'contactPoint' => [
                '@type'           => 'ContactPoint',
                'telephone'       => '+17865683345',
                'contactType'     => 'customer service',
                'contactOption'   => 'TollFree',
                'availableLanguage' => 'Spanish',
            ],
            'areaServed' => 'CO',
        ],
        [
            '@type'       => 'WebSite',
            '@id'         => $siteUrl . '/#website',
            'url'         => $siteUrl,
            'name'        => 'FromUSA.com.co',
            'description' => 'La plaza de comercio online más grande de Colombia para productos importados de USA',
            'inLanguage'  => 'es-CO',
            'publisher'   => ['@id' => $siteUrl . '/#organization'],
        ],
        [
            '@type'           => 'OnlineBusiness',
            '@id'             => $siteUrl . '/#marketplace',
            'name'            => 'FromUSA.com.co',
            'url'             => $siteUrl,
            'description'     => 'La plaza de comercio online más grande de Colombia para productos importados directo de USA. Plataforma intermediaria: conecta vendedores con compradores. Negociación por WhatsApp. Sin comisiones.',
            'areaServed'      => ['@type' => 'Country', 'name' => 'Colombia'],
            'hasOfferCatalog' => ['@id' => $siteUrl . '/#catalog'],
        ],
        [
            '@type'           => 'OfferCatalog',
            '@id'             => $siteUrl . '/#catalog',
            'name'            => 'Catálogo de productos importados de USA — FromUSA.com.co',
            'numberOfItems'   => count($products),
            'itemListElement' => array_map(static function (array $p, int $i) use ($siteUrl): array {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'item'     => [
                        '@type'       => 'Product',
                        'name'        => $p['name'],
                        'description' => $p['description'] ?: $p['name'],
                        'brand'       => ['@type' => 'Brand', 'name' => $p['brand'] ?: 'FromUSA'],
                        'offers'      => [
                            '@type'           => 'Offer',
                            'priceCurrency'   => 'COP',
                            'price'           => $p['price'],
                            'availability'    => 'https://schema.org/InStock',
                            'seller'          => ['@type' => 'Person', 'name' => 'Vendedor independiente'],
                        ],
                    ],
                ];
                if (!empty($p['images'][0])) {
                    $item['item']['image'] = $siteUrl . $p['images'][0];
                }
                if ($p['market_price'] && $p['market_price'] > $p['price']) {
                    $item['item']['offers']['priceValidUntil'] = '2027-12-31';
                    $item['item']['offers']['discount'] = $p['discount'] . '%';
                }
                return $item;
            }, $topProducts, array_keys($topProducts)),
        ],
    ],
];
echo '  <script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
?>

  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy:    '#0A1628',
            usared:  '#B22234',
            usablue: '#3C3B6E',
            usalight:'#F5F5F7'
          },
          fontFamily: {
            display: ['Bebas Neue', 'sans-serif'],
            body:    ['DM Sans', 'sans-serif']
          }
        }
      }
    }
  </script>
  <style>
    html, body { height: 100%; margin: 0; box-sizing: border-box; }
    .cart-badge { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }
    .product-card { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
    .product-card:active { transform: scale(0.97); }
    .product-card:hover { box-shadow: 0 6px 28px rgba(0,0,0,0.10); }
    .fade-in { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .category-pill { scroll-snap-align: start; }
    .categories-scroll { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
    .categories-scroll::-webkit-scrollbar { display: none; }
    .slide-up { animation: slideUp 0.35s ease; }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .card-slides { transition: transform 0.5s ease; }
    /* Modal producto */
    #product-modal { display: none; }
    #product-modal.open { display: flex; align-items: flex-end; justify-content: center; }
    @media (min-width: 768px) { #product-modal.open { align-items: center; } }
  </style>
</head>
<body class="bg-usalight font-body text-navy">

<?php
function fmt(int $n): string {
    return '$' . number_format($n, 0, ',', '.');
}

$categoryEmoji = [
    'celulares'   => '📱',
    'audifonos'   => '🎧',
    'tablets'     => '📲',
    'consolas'    => '🎮',
    'parlantes'   => '🔊',
    'cargadores'  => '🔋',
    'camaras'     => '📷',
    'iluminacion' => '💡',
    'gafas'       => '🕶️',
    'apple'       => '🍎',
    'otros'       => '📦',
];
?>

<div id="app" class="min-h-screen w-full flex flex-col">

  <!-- ── Header unificado (logo + categorías + marcas) ── -->
  <header class="sticky top-0 z-50 shadow-md">

    <!-- Fila logo -->
    <div class="bg-navy text-white px-5 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <!-- Mini bandera USA -->
        <svg width="36" height="24" viewBox="0 0 36 24" class="rounded flex-shrink-0 ring-1 ring-white/30" style="filter:drop-shadow(0 1px 3px rgba(0,0,0,0.5))">
          <rect width="36" height="24" fill="#B22234"/>
          <rect y="1.85"  width="36" height="1.85" fill="white"/>
          <rect y="5.54"  width="36" height="1.85" fill="white"/>
          <rect y="9.23"  width="36" height="1.85" fill="white"/>
          <rect y="12.92" width="36" height="1.85" fill="white"/>
          <rect y="16.62" width="36" height="1.85" fill="white"/>
          <rect y="20.31" width="36" height="1.85" fill="white"/>
          <rect width="15" height="13" fill="#3C3B6E"/>
          <circle cx="3"   cy="2.6"  r="1" fill="white"/>
          <circle cx="7.5" cy="2.6"  r="1" fill="white"/>
          <circle cx="12"  cy="2.6"  r="1" fill="white"/>
          <circle cx="3"   cy="6.5"  r="1" fill="white"/>
          <circle cx="7.5" cy="6.5"  r="1" fill="white"/>
          <circle cx="12"  cy="6.5"  r="1" fill="white"/>
          <circle cx="3"   cy="10.4" r="1" fill="white"/>
          <circle cx="7.5" cy="10.4" r="1" fill="white"/>
          <circle cx="12"  cy="10.4" r="1" fill="white"/>
        </svg>
        <!-- Texto logo -->
        <div class="flex items-baseline gap-1.5 leading-none select-none">
          <span class="text-white text-[11px] font-medium tracking-[0.18em] uppercase">from</span>
          <span class="font-display text-[30px] tracking-wide leading-none" style="color:#E8253A;text-shadow:0 0 18px rgba(232,37,58,0.45)">USA</span>
          <span class="text-white/75 text-[12px] font-medium">.com.co</span>
        </div>
      </div>
      <button id="cart-btn" class="relative p-2" onclick="toggleCart()">
        <i data-lucide="shopping-bag" class="w-6 h-6"></i>
        <span id="cart-count"
          class="absolute -top-1 -right-1 bg-usared text-white text-xs w-5 h-5 rounded-full
                 flex items-center justify-center cart-badge hidden">0</span>
      </button>
    </div>

    <!-- Fila categorías -->
    <div class="bg-white border-b border-gray-200">
      <div class="categories-scroll flex overflow-x-auto" id="categories-bar">
        <?php foreach ($categories as $cat): ?>
        <button
          onclick="filterCategory('<?= htmlspecialchars($cat['id']) ?>')"
          data-cat="<?= htmlspecialchars($cat['id']) ?>"
          class="category-pill flex-shrink-0 px-5 py-3 text-sm whitespace-nowrap transition-colors
                 <?= $cat['id'] === 'all'
                     ? 'font-semibold text-navy border-b-2 border-navy'
                     : 'font-medium text-gray-500 border-b-2 border-transparent hover:text-navy' ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Fila marcas (se desliza cuando hay ≥2 marcas en la categoría activa) -->
    <div id="brands-section"
      class="bg-white overflow-hidden"
      style="max-height:0; transition:max-height 0.25s ease, border-bottom 0.25s ease">
      <div class="categories-scroll flex overflow-x-auto border-b border-gray-100" id="brands-bar"></div>
    </div>

  </header>

  <!-- ── Main ─────────────────────────────────────── -->
  <main class="flex-1" id="main-content">

    <!-- Título sección productos -->
    <section class="px-4 md:px-6 pb-3 pt-1">
      <div class="flex items-end justify-between">
        <div>
          <h2 class="font-bold text-2xl leading-tight">Productos</h2>
          <p class="text-xs text-gray-400 font-medium mt-0.5">Importados directo de USA</p>
        </div>
        <span class="text-xs text-usared font-semibold bg-red-50 px-2.5 py-1 rounded-full">
          <?= count($products) ?> artículos
        </span>
      </div>
    </section>

    <!-- Grid de productos -->
    <section class="px-4 md:px-6 pb-28">
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4" id="products-container">
        <?php foreach ($products as $p):
          $hasImg    = !empty($p['images']);
          $emoji     = $categoryEmoji[$p['category']] ?? '📦';
          $discount  = $p['discount'];
          $showBadge = $discount >= 10;
        ?>
        <div
          class="product-card bg-white rounded-2xl p-3.5 shadow-[0_2px_16px_rgba(0,0,0,0.07)] relative fade-in"
          data-category="<?= htmlspecialchars($p['category']) ?>"
          data-brand="<?= htmlspecialchars($p['brand']) ?>"
          data-id="<?= $p['id'] ?>"
          onclick="openModal(<?= $p['id'] ?>)">

          <!-- Badge Apple -->
          <?php if ($p['brand'] === 'Apple'): ?>
          <div class="absolute top-2.5 left-2.5 z-10">
            <span class="bg-navy text-white text-[10px] font-bold px-2 py-0.5 rounded-full tracking-wide">Apple</span>
          </div>
          <?php endif; ?>

          <!-- Badge descuento -->
          <?php if ($showBadge): ?>
          <div class="absolute top-2.5 right-2.5 z-10">
            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">−<?= $discount ?>%</span>
          </div>
          <?php endif; ?>

          <!-- Carrusel de imágenes -->
          <div class="card-img-wrap relative w-full aspect-square rounded-xl overflow-hidden mb-3 bg-white">
            <div class="card-slides flex h-full w-full">
              <?php if ($hasImg): ?>
                <?php foreach ($p['images'] as $imgSrc): ?>
                <img
                  src="<?= htmlspecialchars($imgSrc) ?>"
                  alt="<?= htmlspecialchars($p['name']) ?>"
                  class="flex-shrink-0 w-full h-full object-contain"
                  loading="lazy">
                <?php endforeach; ?>
              <?php else: ?>
                <div class="flex-shrink-0 w-full h-full flex items-center justify-center">
                  <i data-lucide="package" class="w-14 h-14 text-gray-200"></i>
                </div>
              <?php endif; ?>
            </div>
            <?php if (count($p['images']) > 1): ?>
            <div class="absolute bottom-1.5 left-0 right-0 flex justify-center gap-1 pointer-events-none">
              <?php foreach ($p['images'] as $i => $_): ?>
              <span class="slide-dot w-1.5 h-1.5 rounded-full <?= $i === 0 ? 'bg-navy/70' : 'bg-navy/20' ?>"></span>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- Info tarjeta -->
          <h4 class="font-semibold text-xs leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h4>

          <?php if ($p['market_price'] !== null && $p['market_price'] > $p['price']): ?>
          <p class="text-gray-400 text-[11px] line-through leading-none mb-0.5"><?= fmt($p['market_price']) ?></p>
          <?php endif; ?>

          <p class="text-usared font-bold text-base mb-2.5"><?= fmt($p['price']) ?></p>

          <button
            onclick="addToCart(<?= $p['id'] ?>); event.stopPropagation();"
            class="w-full bg-navy text-white text-xs font-semibold py-2 rounded-xl
                   flex items-center justify-center gap-1 active:bg-usablue transition">
            <i data-lucide="plus" class="w-3 h-3"></i> Agregar
          </button>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

  </main>

  <!-- ── Footer ──────────────────────────────────────────────────── -->
  <footer class="bg-navy text-white mt-8">

    <!-- Bloque principal -->
    <div class="max-w-5xl mx-auto px-5 pt-10 pb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

      <!-- Columna 1: Marca -->
      <div class="lg:col-span-1">
        <div class="flex items-baseline gap-1.5 leading-none select-none mb-3">
          <span class="text-white/60 text-[10px] font-medium tracking-[0.18em] uppercase">from</span>
          <span class="font-display text-[28px] tracking-wide leading-none" style="color:#E8253A">USA</span>
          <span class="text-white/50 text-[11px] font-medium">.com.co</span>
        </div>
        <p class="text-white/70 text-xs leading-relaxed font-medium">
          El mercado que Colombia necesitaba. Todo lo que llega de USA, en un solo lugar.
        </p>
        <p class="text-white/35 text-[11px] leading-relaxed mt-3">
          Conectamos a quienes tienen los productos con quienes los buscan. Sin comisiones, sin burocracia, sin rodeos.
        </p>
      </div>

      <!-- Columna 2: El marketplace (links SEO) -->
      <div>
        <h3 class="text-white/80 text-xs font-semibold uppercase tracking-widest mb-3">El marketplace</h3>
        <ul class="space-y-2 text-white/55 text-xs">
          <li><a href="/marketplace"  class="hover:text-white transition hover:underline">¿Qué es FromUSA.com.co?</a></li>
          <li><a href="/como-comprar" class="hover:text-white transition hover:underline">Cómo comprar importados de USA</a></li>
          <li><a href="/faq"          class="hover:text-white transition hover:underline">Preguntas frecuentes</a></li>
          <li><a href="/vende"        class="hover:text-white transition hover:underline">Quiero vender aquí</a></li>
          <li><a href="/terminos"     class="hover:text-white transition hover:underline">Términos y condiciones</a></li>
        </ul>
      </div>

      <!-- Columna 3: Colombia entera (ciudades SEO) -->
      <div>
        <h3 class="text-white/80 text-xs font-semibold uppercase tracking-widest mb-3">Colombia entera</h3>
        <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-white/55 text-xs">
          <a href="/ciudad/bogota"        class="hover:text-white transition hover:underline">Bogotá</a>
          <a href="/ciudad/medellin"      class="hover:text-white transition hover:underline">Medellín</a>
          <a href="/ciudad/cali"          class="hover:text-white transition hover:underline">Cali</a>
          <a href="/ciudad/barranquilla"  class="hover:text-white transition hover:underline">Barranquilla</a>
          <a href="/ciudad/bucaramanga"   class="hover:text-white transition hover:underline">Bucaramanga</a>
          <a href="/ciudad/cartagena"     class="hover:text-white transition hover:underline">Cartagena</a>
          <a href="/ciudad/cucuta"        class="hover:text-white transition hover:underline">Cúcuta</a>
          <a href="/ciudad/pereira"       class="hover:text-white transition hover:underline">Pereira</a>
          <a href="/ciudad/santa-marta"   class="hover:text-white transition hover:underline">Santa Marta</a>
          <a href="/ciudad/manizales"     class="hover:text-white transition hover:underline">Manizales</a>
          <a href="/ciudad/ibague"        class="hover:text-white transition hover:underline">Ibagué</a>
          <a href="/ciudad/pasto"         class="hover:text-white transition hover:underline">Pasto</a>
          <a href="/ciudad/armenia"       class="hover:text-white transition hover:underline">Armenia</a>
          <a href="/ciudad/villavicencio" class="hover:text-white transition hover:underline">Villavicencio</a>
          <a href="/ciudad/monteria"      class="hover:text-white transition hover:underline">Montería</a>
        </div>
      </div>

      <!-- Columna 4: ¿Tienes productos de USA? -->
      <div>
        <h3 class="text-white/80 text-xs font-semibold uppercase tracking-widest mb-3">¿Tienes productos de USA?</h3>
        <p class="text-white/55 text-xs leading-relaxed mb-4">
          Publícalos aquí y llega a miles de colombianos que los están buscando ahora mismo. Gratis. Sin comisiones.
        </p>
        <ul class="space-y-1.5 text-white/40 text-xs mb-5">
          <li>— Vendedores certificados</li>
          <li>— Toda Colombia como mercado</li>
          <li>— Tratos directos por WhatsApp</li>
        </ul>
        <a href="/vende"
           class="inline-flex items-center gap-1.5 bg-green-600/20 hover:bg-green-600/30 border border-green-600/30
                  text-green-400 text-xs px-3 py-2 rounded-lg transition">
          Quiero publicar mis productos
        </a>
      </div>

    </div>

    <!-- Barra inferior: legal y créditos -->
    <div class="border-t border-white/10">
      <div class="max-w-5xl mx-auto px-5 py-3 pb-20 sm:pb-3 flex flex-wrap items-center justify-between gap-x-4 gap-y-1 text-[10px] text-white/30">
        <span>
          &copy; <?= date('Y') ?> FromUSA.com.co — Todos los derechos reservados &mdash;
          <a href="/terminos" class="hover:text-white/60 transition hover:underline">Términos y Condiciones</a>
        </span>
        <span>Desarrollado por
          <a href="https://111labs.net" target="_blank" rel="noopener noreferrer" class="hover:text-white/60 transition hover:underline font-semibold">111labs S.A.S</a>
        </span>
      </div>
    </div>

  </footer>

  <!-- ── Carrito overlay ──────────────────────────── -->
  <div id="cart-overlay" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleCart()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[80%] flex flex-col slide-up">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-lg">Tu Carrito</h3>
        <button onclick="toggleCart()" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
      <div id="cart-items" class="flex-1 overflow-auto p-5">
        <p class="text-center text-gray-400 py-8 text-sm">Tu carrito está vacío</p>
      </div>
      <div id="cart-footer" class="p-5 border-t border-gray-100 hidden">
        <div class="flex justify-between mb-4">
          <span class="font-semibold text-gray-600">Total:</span>
          <span id="cart-total" class="font-bold text-usared text-xl">$0</span>
        </div>
        <button onclick="sendWhatsApp()"
          class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-2xl
                 flex items-center justify-center gap-2 transition">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
          </svg>
          Comprar por WhatsApp
        </button>
      </div>
    </div>
  </div>

  <!-- ── Modal detalle de producto ────────────────── -->
  <div id="product-modal" class="fixed inset-0 z-[200]">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>

    <div id="modal-panel"
      class="relative z-10 bg-white w-full rounded-t-3xl
             md:rounded-2xl md:max-w-4xl md:w-[92%]
             max-h-[92vh] md:max-h-[88vh]
             flex flex-col md:flex-row overflow-hidden slide-up">

      <!-- Columna izquierda: galería -->
      <div class="md:w-[42%] md:flex-shrink-0 flex flex-col md:border-r md:border-gray-100">

        <div class="flex items-center justify-between px-4 pt-4 pb-2 flex-shrink-0 md:hidden">
          <span id="modal-brand-badge" class="text-xs font-bold text-usablue uppercase tracking-wide"></span>
          <button onclick="closeModal()" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>

        <div class="relative mx-4 md:mx-0 rounded-2xl md:rounded-none overflow-hidden bg-white
                    aspect-square md:aspect-auto md:flex-1">
          <img id="modal-main-img" src="" alt="" class="w-full h-full object-contain">
          <div id="modal-emoji-fallback"
            class="hidden absolute inset-0 bg-gray-50 items-center justify-center text-6xl">
          </div>
          <button id="modal-prev" onclick="modalNav(-1)"
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/25 text-white w-9 h-9 rounded-full
                   flex items-center justify-center hidden hover:bg-black/45 transition">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
          </button>
          <button id="modal-next" onclick="modalNav(1)"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/25 text-white w-9 h-9 rounded-full
                   flex items-center justify-center hidden hover:bg-black/45 transition">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
          </button>
          <div id="modal-dots"
            class="absolute bottom-2 left-0 right-0 flex justify-center gap-1.5 pointer-events-none">
          </div>
        </div>

        <div id="modal-thumbs" class="flex gap-2 px-4 py-3 overflow-x-auto flex-shrink-0"></div>
      </div>

      <!-- Columna derecha: información -->
      <div class="flex-1 flex flex-col overflow-hidden">

        <div class="hidden md:flex items-center justify-between px-6 pt-5 pb-3 flex-shrink-0">
          <span id="modal-brand-badge-desk" class="text-sm font-bold text-usablue uppercase tracking-wide"></span>
          <button onclick="closeModal()"
            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto px-4 md:px-6 pb-6 pt-3 md:pt-0">
          <h2 id="modal-name" class="font-bold text-xl leading-snug mb-0.5"></h2>
          <p id="modal-model" class="text-sm text-gray-500 mb-1"></p>
          <p id="modal-meta" class="text-xs text-gray-400 mb-5"></p>

          <div class="mb-2">
            <div id="modal-prices" class="flex flex-wrap items-end gap-2 mb-1"></div>
            <p id="modal-usd" class="text-xs text-gray-400"></p>
          </div>

          <div id="modal-stock" class="flex items-center gap-1.5 text-xs font-semibold mt-3 mb-5"></div>

          <hr class="mb-5 border-gray-100">

          <p id="modal-desc" class="text-sm text-gray-600 leading-relaxed mb-6"></p>

          <button id="modal-add-btn"
            class="w-full bg-navy text-white font-bold py-3.5 rounded-2xl
                   flex items-center justify-center gap-2 active:bg-usablue transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Agregar al carrito
          </button>
        </div>
      </div>

    </div>
  </div>


  <!-- ── WhatsApp float ────────────────────────────── -->
  <a
    href="https://wa.me/17865683345?text=<?= urlencode('¡Hola! Vi sus productos importados de USA y me gustaría saber más 🇺🇸') ?>"
    target="_blank"
    rel="noopener noreferrer"
    class="fixed bottom-5 right-5 z-50 bg-green-500 text-white w-14 h-14 rounded-full
           flex items-center justify-center shadow-xl hover:scale-110 transition">
    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
    </svg>
  </a>

</div><!-- /#app -->

<script>
window.catalogProducts = <?= json_encode($products, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script>
const WHATSAPP_NUMBER = '17865683345';

let cart           = [];
let activeCategory = 'all';
let activeBrand    = 'all';

// ── Utilidades ───────────────────────────────────
function fmt(n) {
  return '$' + Number(n).toLocaleString('es-CO');
}

function getEmoji(cat) {
  return {celulares:'📱',audifonos:'🎧',tablets:'📲',consolas:'🎮',
          parlantes:'🔊',cargadores:'🔋',camaras:'📷',iluminacion:'💡',
          gafas:'🕶️',apple:'🍎',otros:'📦'}[cat] || '📦';
}

// ── Carrusel en tarjetas ─────────────────────────
function initCardSlideshows() {
  document.querySelectorAll('.card-img-wrap').forEach(wrap => {
    const track = wrap.querySelector('.card-slides');
    const dots  = wrap.querySelectorAll('.slide-dot');
    const count = track.children.length;
    if (count <= 1) return;
    let idx = 0;
    setInterval(() => {
      idx = (idx + 1) % count;
      track.style.transform = `translateX(-${idx * 100}%)`;
      dots.forEach((d, i) => { d.style.opacity = i === idx ? '1' : '0.3'; });
    }, 2800);
  });
}

// ── Filtros ──────────────────────────────────────
function applyFilters() {
  document.querySelectorAll('[data-category]').forEach(card => {
    const catOk   = activeCategory === 'all' || card.dataset.category === activeCategory;
    const brandOk = activeBrand   === 'all' || card.dataset.brand     === activeBrand;
    card.style.display = (catOk && brandOk) ? '' : 'none';
  });
}

function filterCategory(id) {
  activeCategory = id;
  activeBrand    = 'all';

  document.querySelectorAll('[data-cat]').forEach(btn => {
    const on = btn.dataset.cat === id;
    btn.classList.toggle('font-semibold',      on);
    btn.classList.toggle('text-navy',          on);
    btn.classList.toggle('border-navy',        on);
    btn.classList.toggle('font-medium',        !on);
    btn.classList.toggle('text-gray-500',      !on);
    btn.classList.toggle('border-transparent', !on);
  });

  buildBrandsBar(id);
  applyFilters();
}

function filterBrand(brand) {
  activeBrand = brand;
  document.querySelectorAll('[data-brand-btn]').forEach(btn => {
    const on = btn.dataset.brandBtn === brand;
    btn.classList.toggle('font-semibold',      on);
    btn.classList.toggle('text-usablue',       on);
    btn.classList.toggle('border-usablue',     on);
    btn.classList.toggle('font-medium',        !on);
    btn.classList.toggle('text-gray-500',      !on);
    btn.classList.toggle('border-transparent', !on);
  });
  applyFilters();
}

function buildBrandsBar(categoryId) {
  const section = document.getElementById('brands-section');
  if (categoryId === 'all') { section.style.maxHeight = '0'; return; }

  const inCat  = window.catalogProducts.filter(p => p.category === categoryId);
  const brands = [...new Set(inCat.map(p => p.brand).filter(Boolean))].sort();
  if (brands.length <= 1) { section.style.maxHeight = '0'; return; }

  const bar = document.getElementById('brands-bar');
  bar.innerHTML = ['all', ...brands].map(b => {
    const isAll = b === 'all';
    return `<button
      onclick="filterBrand('${b.replace(/'/g, "\\'")}')"
      data-brand-btn="${b.replace(/"/g, '&quot;')}"
      class="category-pill flex-shrink-0 px-4 py-2.5 text-xs whitespace-nowrap transition-colors
             ${isAll ? 'font-semibold text-usablue border-b-2 border-usablue' : 'font-medium text-gray-500 border-b-2 border-transparent hover:text-navy'}">
      ${isAll ? 'Todas' : b}
    </button>`;
  }).join('');
  section.style.maxHeight = '44px';
}

// ── Carrito ──────────────────────────────────────
function addToCart(productId) {
  const p = window.catalogProducts.find(x => x.id === productId);
  if (!p) return;
  const existing = cart.find(x => x.id === productId);
  if (existing) existing.qty++;
  else cart.push({ ...p, qty: 1 });
  updateCartBadge();

  const btn = event.target.closest('button');
  const original = btn.innerHTML;
  btn.textContent = '✓ Agregado';
  btn.classList.add('bg-green-600');
  setTimeout(() => {
    btn.innerHTML = original;
    btn.classList.remove('bg-green-600');
    lucide.createIcons();
  }, 800);
}

function updateCartBadge() {
  const count = cart.reduce((s, i) => s + i.qty, 0);
  const badge = document.getElementById('cart-count');
  badge.textContent = count;
  badge.classList.toggle('hidden', count === 0);
}

function toggleCart() {
  document.getElementById('cart-overlay').classList.toggle('hidden');
  renderCart();
}

function renderCart() {
  const itemsEl  = document.getElementById('cart-items');
  const footerEl = document.getElementById('cart-footer');
  if (cart.length === 0) {
    itemsEl.innerHTML = '<p class="text-center text-gray-400 py-8 text-sm">Tu carrito está vacío</p>';
    footerEl.classList.add('hidden');
    return;
  }
  footerEl.classList.remove('hidden');
  itemsEl.innerHTML = cart.map(item => `
    <div class="flex items-center gap-3 py-3 border-b border-gray-50">
      <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
        ${item.images && item.images[0]
          ? `<img src="${item.images[0]}" class="w-full h-full object-contain" alt="">`
          : `<span class="text-xl">${getEmoji(item.category)}</span>`}
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold truncate">${item.name}</p>
        <p class="text-xs text-usared font-bold">${fmt(item.price)}</p>
      </div>
      <div class="flex items-center gap-2">
        <button onclick="changeQty(${item.id}, -1)"
          class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-sm font-bold">−</button>
        <span class="text-sm font-semibold w-4 text-center">${item.qty}</span>
        <button onclick="changeQty(${item.id}, 1)"
          class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-sm font-bold">+</button>
      </div>
    </div>
  `).join('');
  document.getElementById('cart-total').textContent = fmt(cart.reduce((s, i) => s + i.price * i.qty, 0));
}

function changeQty(id, delta) {
  const item = cart.find(x => x.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) cart = cart.filter(x => x.id !== id);
  updateCartBadge();
  renderCart();
}

function sendWhatsApp() {
  let msg = '¡Hola! 👋 Me interesan estos productos de FromUSA.com.co:\n\n';
  cart.forEach(i => { msg += `• ${i.name} x${i.qty} — ${fmt(i.price * i.qty)}\n`; });
  msg += `\n💰 Total: ${fmt(cart.reduce((s, i) => s + i.price * i.qty, 0))}\n\n¿Están disponibles?`;
  window.open(`https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(msg)}`, '_blank');
}

// ── Modal de detalle ─────────────────────────────
let currentProduct = null;
let modalImgIdx    = 0;

function openModal(id) {
  const p = window.catalogProducts.find(x => x.id === id);
  if (!p) return;
  currentProduct = p;
  modalImgIdx    = 0;

  const brandText = p.brand || '';
  document.getElementById('modal-brand-badge').textContent      = brandText;
  document.getElementById('modal-brand-badge-desk').textContent = brandText;

  document.getElementById('modal-name').textContent = p.name;

  const modelEl  = document.getElementById('modal-model');
  const modelVal = (p.model && p.model !== p.name) ? p.model : '';
  modelEl.textContent = modelVal;
  modelEl.classList.toggle('hidden', !modelVal);

  const parts = [p.brand, p.capacity].filter(Boolean);
  document.getElementById('modal-meta').textContent = parts.join(' · ');

  const pricesEl = document.getElementById('modal-prices');
  let ph = `<span class="text-usared font-bold text-2xl">${fmt(p.price)}</span>`;
  if (p.market_price && p.market_price > p.price) {
    ph += `<span class="text-gray-400 text-base line-through">${fmt(p.market_price)}</span>`;
    if (p.discount >= 5) {
      ph += `<span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">−${p.discount}%</span>`;
    }
  }
  pricesEl.innerHTML = ph;

  const usdEl = document.getElementById('modal-usd');
  if (p.price_usd) {
    usdEl.textContent = `Precio referencia: USD $${p.price_usd}`;
    usdEl.classList.remove('hidden');
  } else {
    usdEl.classList.add('hidden');
  }

  const stockEl = document.getElementById('modal-stock');
  if (p.stock > 0) {
    stockEl.innerHTML =
      `<span class="w-2 h-2 rounded-full bg-green-500 inline-block flex-shrink-0"></span>
       <span class="text-green-700">En stock</span>
       <span class="text-gray-400 font-normal">· ${p.stock} ${p.stock === 1 ? 'unidad' : 'unidades'} disponibles</span>`;
  } else {
    stockEl.innerHTML =
      `<span class="w-2 h-2 rounded-full bg-red-400 inline-block flex-shrink-0"></span>
       <span class="text-red-600">Sin stock</span>`;
  }

  document.getElementById('modal-desc').textContent = p.description || '';

  const addBtn = document.getElementById('modal-add-btn');
  addBtn.onclick = () => {
    const existing = cart.find(x => x.id === p.id);
    if (existing) existing.qty++;
    else cart.push({ ...p, qty: 1 });
    updateCartBadge();
    addBtn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Agregado';
    addBtn.classList.add('bg-green-600');
    lucide.createIcons();
    setTimeout(() => {
      addBtn.innerHTML = '<i data-lucide="plus" class="w-4 h-4"></i> Agregar al carrito';
      addBtn.classList.remove('bg-green-600');
      lucide.createIcons();
    }, 1200);
  };

  renderModalGallery();
  document.getElementById('product-modal').classList.add('open');
  document.body.style.overflow = 'hidden';
  lucide.createIcons();
}

function renderModalGallery() {
  const p      = currentProduct;
  const hasImg = p.images && p.images.length > 0;
  const mainImg   = document.getElementById('modal-main-img');
  const emojiFall = document.getElementById('modal-emoji-fallback');

  if (hasImg) {
    mainImg.src = p.images[modalImgIdx];
    mainImg.alt = p.name;
    mainImg.classList.remove('hidden');
    emojiFall.classList.remove('flex');
    emojiFall.classList.add('hidden');
  } else {
    mainImg.classList.add('hidden');
    emojiFall.innerHTML = getEmoji(p.category);
    emojiFall.classList.remove('hidden');
    emojiFall.classList.add('flex');
  }

  const multiImg = hasImg && p.images.length > 1;
  document.getElementById('modal-prev').classList.toggle('hidden', !multiImg);
  document.getElementById('modal-next').classList.toggle('hidden', !multiImg);

  document.getElementById('modal-dots').innerHTML = multiImg
    ? p.images.map((_, i) =>
        `<span class="w-2 h-2 rounded-full ${i === modalImgIdx ? 'bg-white' : 'bg-white/40'}"></span>`
      ).join('')
    : '';

  document.getElementById('modal-thumbs').innerHTML = multiImg
    ? p.images.map((src, i) => `
        <button onclick="setModalImg(${i})"
          class="flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden border-2 transition bg-white
                 ${i === modalImgIdx ? 'border-usared' : 'border-transparent opacity-50 hover:opacity-80'}">
          <img src="${src}" class="w-full h-full object-contain" loading="lazy">
        </button>`
      ).join('')
    : '';
}

function setModalImg(idx) { modalImgIdx = idx; renderModalGallery(); }

function modalNav(dir) {
  const len = currentProduct.images.length;
  modalImgIdx = (modalImgIdx + dir + len) % len;
  renderModalGallery();
}

function closeModal() {
  document.getElementById('product-modal').classList.remove('open');
  document.body.style.overflow = '';
  currentProduct = null;
}


// ── Teclado global ───────────────────────────────
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeModal(); }
});

// ── Init ─────────────────────────────────────────
initCardSlideshows();
lucide.createIcons();
</script>
</body>
</html>
