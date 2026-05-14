<?php
$ciudades = [
    'bogota'        => ['name'=>'Bogotá',        'dep'=>'Cundinamarca', 'desc'=>'la capital del país'],
    'medellin'      => ['name'=>'Medellín',       'dep'=>'Antioquia',   'desc'=>'la ciudad de la innovación'],
    'cali'          => ['name'=>'Cali',           'dep'=>'Valle del Cauca','desc'=>'la sucursal del cielo'],
    'barranquilla'  => ['name'=>'Barranquilla',   'dep'=>'Atlántico',   'desc'=>'la puerta de oro de Colombia'],
    'bucaramanga'   => ['name'=>'Bucaramanga',    'dep'=>'Santander',   'desc'=>'la ciudad bonita'],
    'cartagena'     => ['name'=>'Cartagena',      'dep'=>'Bolívar',     'desc'=>'la ciudad heroica'],
    'cucuta'        => ['name'=>'Cúcuta',         'dep'=>'Norte de Santander','desc'=>'la ciudad fronteriza'],
    'pereira'       => ['name'=>'Pereira',        'dep'=>'Risaralda',   'desc'=>'la querendona, trasnochadora y morena'],
    'santa-marta'   => ['name'=>'Santa Marta',    'dep'=>'Magdalena',   'desc'=>'la ciudad más antigua de Colombia'],
    'manizales'     => ['name'=>'Manizales',      'dep'=>'Caldas',      'desc'=>'la ciudad de las puertas abiertas'],
    'ibague'        => ['name'=>'Ibagué',         'dep'=>'Tolima',      'desc'=>'la ciudad musical de Colombia'],
    'pasto'         => ['name'=>'Pasto',          'dep'=>'Nariño',      'desc'=>'la ciudad sorpresa'],
    'armenia'       => ['name'=>'Armenia',        'dep'=>'Quindío',     'desc'=>'la ciudad milagro de Colombia'],
    'villavicencio' => ['name'=>'Villavicencio',  'dep'=>'Meta',        'desc'=>'la puerta del llano'],
    'monteria'      => ['name'=>'Montería',       'dep'=>'Córdoba',     'desc'=>'la capital ganadera de Colombia'],
];

$slug = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$slug = str_replace('ciudad/', '', $slug);

if (!array_key_exists($slug, $ciudades)) {
    header('Location: /', true, 301);
    exit;
}

$ciudad = $ciudades[$slug];
$name   = $ciudad['name'];
$dep    = $ciudad['dep'];
$desc   = $ciudad['desc'];

$otherCities = array_filter($ciudades, fn($k) => $k !== $slug, ARRAY_FILTER_USE_KEY);
?>
<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Tecnología importada de USA en <?= htmlspecialchars($name) ?> — FromUSA.com.co</title>
  <meta name="description" content="Compra tecnología importada de Estados Unidos en <?= htmlspecialchars($name) ?>, <?= htmlspecialchars($dep) ?>. Celulares, audífonos, tablets y consolas originales con vendedores certificados. Envío a toda Colombia.">
  <meta name="keywords" content="importados USA <?= htmlspecialchars(strtolower($name)) ?>, tecnología americana <?= htmlspecialchars(strtolower($name)) ?>, celulares importados <?= htmlspecialchars(strtolower($name)) ?>, comprar importados <?= htmlspecialchars(strtolower($name)) ?> colombia">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://fromusa.com.co/ciudad/<?= htmlspecialchars($slug) ?>">
  <meta name="author" content="FromUSA.com.co — 111labs S.A.S">
  <link rel="icon" type="image/png" href="/bandera.png">
  <link rel="apple-touch-icon" href="/bandera.png">

  <meta property="og:type"        content="website">
  <meta property="og:url"         content="https://fromusa.com.co/ciudad/<?= htmlspecialchars($slug) ?>">
  <meta property="og:site_name"   content="FromUSA.com.co">
  <meta property="og:locale"      content="es_CO">
  <meta property="og:title"       content="Tecnología importada de USA en <?= htmlspecialchars($name) ?> — FromUSA.com.co">
  <meta property="og:description" content="Compra tecnología importada de Estados Unidos en <?= htmlspecialchars($name) ?>. Vendedores certificados, envío a domicilio, tratos directos por WhatsApp.">

  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { navy:'#0A1628', usared:'#B22234', usablue:'#3C3B6E', usalight:'#F5F5F7' },
          fontFamily: { display:['"Bebas Neue"','sans-serif'], body:['"DM Sans"','sans-serif'] }
        }
      }
    }
  </script>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Tecnología importada de USA en <?= htmlspecialchars($name) ?>",
    "description": "Compra tecnología importada de Estados Unidos en <?= htmlspecialchars($name) ?>, <?= htmlspecialchars($dep) ?>. Vendedores certificados en FromUSA.com.co.",
    "url": "https://fromusa.com.co/ciudad/<?= htmlspecialchars($slug) ?>",
    "inLanguage": "es-CO",
    "publisher": {
      "@type": "Organization",
      "name": "FromUSA.com.co",
      "url": "https://fromusa.com.co",
      "areaServed": {"@type": "City", "name": "<?= htmlspecialchars($name) ?>", "containedInPlace": {"@type": "AdministrativeArea", "name": "<?= htmlspecialchars($dep) ?>"}}
    }
  }
  </script>
</head>
<body class="bg-usalight font-body text-navy flex flex-col min-h-screen">

  <!-- ── Header ──────────────────────────────────────────────────── -->
  <header class="bg-navy text-white sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-5 py-3 flex items-center justify-between">
      <a href="/" class="flex flex-col leading-none select-none gap-1">
        <div class="flex items-baseline gap-1.5">
          <span class="text-white/60 text-[10px] font-medium tracking-[0.18em] uppercase">from</span>
          <span class="font-display text-[24px] tracking-wide leading-none" style="color:#E8253A">USA</span>
          <span class="text-white/50 text-[11px] font-medium">.com.co</span>
        </div>
        <span class="text-white/35 text-[8px] tracking-[0.2em] uppercase font-medium">De allá · Para acá · Sin rodeos · Marketplace</span>
      </a>
      <a href="/" class="text-white/70 hover:text-white text-sm transition flex items-center gap-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Ver el catálogo
      </a>
    </div>
  </header>

  <main class="flex-1">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <section class="bg-navy text-white">
      <div class="max-w-4xl mx-auto px-5 py-14 sm:py-20 text-center">
        <p class="text-usared text-xs font-semibold uppercase tracking-[0.2em] mb-4">
          <?= htmlspecialchars($dep) ?>, Colombia
        </p>
        <h1 class="font-display text-5xl sm:text-7xl tracking-wide leading-none mb-5">
          Importados de <span style="color:#E8253A">USA</span><br>en <?= htmlspecialchars($name) ?>
        </h1>
        <p class="text-white/65 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto mb-8">
          Si estás en <?= htmlspecialchars($name) ?>, <?= htmlspecialchars($desc) ?>, aquí encuentras tecnología original
          de Estados Unidos con vendedores certificados que llegan hasta tu puerta.
          Celulares, audífonos, tablets, consolas y mucho más.
        </p>
        <a href="/"
           class="inline-flex items-center gap-2 bg-usared hover:bg-red-700 text-white font-semibold text-sm px-8 py-3.5 rounded-xl transition">
          Ver el catálogo completo
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </section>

    <!-- ── Por qué comprar aquí ──────────────────────────────────── -->
    <section class="bg-white border-b border-gray-100">
      <div class="max-w-5xl mx-auto px-5 py-12">
        <h2 class="text-2xl font-bold text-navy text-center mb-2">
          ¿Por qué comprar importados de USA en <?= htmlspecialchars($name) ?>?
        </h2>
        <p class="text-gray-500 text-sm text-center mb-10 max-w-xl mx-auto">
          No tienes que viajar ni esperar meses. Los productos ya están en Colombia, listos para entregarte.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
          <div>
            <div class="w-14 h-14 bg-usalight rounded-2xl border border-gray-200 flex items-center justify-center mx-auto mb-4 text-2xl">🚚</div>
            <h3 class="font-bold text-navy text-sm mb-2">Envío a <?= htmlspecialchars($name) ?></h3>
            <p class="text-gray-500 text-xs leading-relaxed">
              Los vendedores envían a domicilio en <?= htmlspecialchars($name) ?> y a cualquier punto del <?= htmlspecialchars($dep) ?>
              usando las principales mensajerías del país.
            </p>
          </div>
          <div>
            <div class="w-14 h-14 bg-usalight rounded-2xl border border-gray-200 flex items-center justify-center mx-auto mb-4 text-2xl">🇺🇸</div>
            <h3 class="font-bold text-navy text-sm mb-2">Productos originales de USA</h3>
            <p class="text-gray-500 text-xs leading-relaxed">
              Todo lo que encuentras en el catálogo viene de Estados Unidos. Vendedores certificados con documentación del origen de sus productos.
            </p>
          </div>
          <div>
            <div class="w-14 h-14 bg-usalight rounded-2xl border border-gray-200 flex items-center justify-center mx-auto mb-4 text-2xl">💬</div>
            <h3 class="font-bold text-navy text-sm mb-2">Trato directo por WhatsApp</h3>
            <p class="text-gray-500 text-xs leading-relaxed">
              Hablas directo con el vendedor. Sin intermediarios, sin bots. Preguntas, negocias el precio y acuerdas la entrega en tus términos.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Categorías ─────────────────────────────────────────────── -->
    <section class="bg-usalight">
      <div class="max-w-5xl mx-auto px-5 py-12">
        <h2 class="text-2xl font-bold text-navy text-center mb-2">
          Tecnología de USA disponible para <?= htmlspecialchars($name) ?>
        </h2>
        <p class="text-gray-500 text-sm text-center mb-10">
          Explora el catálogo y filtra por la categoría que buscas. Todos los productos se entregan en <?= htmlspecialchars($name) ?>.
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <?php
          $cats = [
            ['emoji'=>'📱','name'=>'Celulares','kw'=>"celulares importados USA {$name}"],
            ['emoji'=>'🎧','name'=>'Audífonos','kw'=>"audífonos originales USA {$name}"],
            ['emoji'=>'📲','name'=>'Tablets',  'kw'=>"tablets importados USA {$name}"],
            ['emoji'=>'🎮','name'=>'Consolas', 'kw'=>"consolas americanas {$name}"],
            ['emoji'=>'🔊','name'=>'Parlantes','kw'=>"parlantes importados USA {$name}"],
            ['emoji'=>'📷','name'=>'Cámaras',  'kw'=>"cámaras importadas USA {$name}"],
            ['emoji'=>'🔋','name'=>'Cargadores','kw'=>"cargadores originales USA {$name}"],
            ['emoji'=>'🍎','name'=>'Apple',    'kw'=>"productos Apple originales {$name}"],
          ];
          foreach ($cats as $c): ?>
          <a href="/"
             class="bg-white rounded-2xl p-5 border border-gray-100 text-center hover:border-navy/20 hover:shadow-sm transition group">
            <div class="text-3xl mb-2"><?= $c['emoji'] ?></div>
            <p class="font-bold text-navy text-sm group-hover:text-usared transition"><?= $c['name'] ?></p>
          </a>
          <?php endforeach; ?>
        </div>
        <div class="text-center mt-8">
          <a href="/"
             class="inline-flex items-center gap-2 bg-navy hover:bg-navy/90 text-white font-semibold text-sm px-7 py-3 rounded-xl transition">
            Ver todo el catálogo
          </a>
        </div>
      </div>
    </section>

    <!-- ── Cómo llega a tu ciudad ────────────────────────────────── -->
    <section class="bg-white border-y border-gray-100">
      <div class="max-w-4xl mx-auto px-5 py-12">
        <h2 class="text-2xl font-bold text-navy text-center mb-2">
          ¿Cómo llega el producto hasta <?= htmlspecialchars($name) ?>?
        </h2>
        <p class="text-gray-500 text-sm text-center mb-10 max-w-xl mx-auto">
          El proceso es simple. Los productos ya están en Colombia: no hay esperas de aduana ni importación.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
          <div>
            <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center mx-auto mb-3">
              <span class="font-display text-white text-lg">1</span>
            </div>
            <p class="text-sm font-semibold text-navy mb-1">Explora el catálogo</p>
            <p class="text-gray-400 text-xs leading-relaxed">Encuentra el producto que buscas entre todos los importados disponibles.</p>
          </div>
          <div>
            <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center mx-auto mb-3">
              <span class="font-display text-white text-lg">2</span>
            </div>
            <p class="text-sm font-semibold text-navy mb-1">Habla con el vendedor</p>
            <p class="text-gray-400 text-xs leading-relaxed">Contacta por WhatsApp, pregunta y negocia el precio directamente.</p>
          </div>
          <div>
            <div class="w-12 h-12 bg-usared rounded-xl flex items-center justify-center mx-auto mb-3">
              <span class="font-display text-white text-lg">3</span>
            </div>
            <p class="text-sm font-semibold text-navy mb-1">Acuerdan la entrega</p>
            <p class="text-gray-400 text-xs leading-relaxed">Definen si es envío a <?= htmlspecialchars($name) ?> o entrega en persona, y el método de pago.</p>
          </div>
          <div>
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
              <span class="font-display text-white text-lg">4</span>
            </div>
            <p class="text-sm font-semibold text-navy mb-1">Recibes tu producto</p>
            <p class="text-gray-400 text-xs leading-relaxed">El importado llega directo a tu puerta en <?= htmlspecialchars($name) ?>, <?= htmlspecialchars($dep) ?>.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Marketplace nacional ──────────────────────────────────── -->
    <section class="bg-navy text-white">
      <div class="max-w-4xl mx-auto px-5 py-12 text-center">
        <h2 class="text-2xl font-bold mb-3">
          El marketplace de importados de USA para toda Colombia
        </h2>
        <p class="text-white/60 text-sm leading-relaxed mb-8 max-w-2xl mx-auto">
          FromUSA.com.co conecta compradores en todo el país con vendedores certificados de tecnología importada de Estados Unidos.
          Sin importar si estás en <?= htmlspecialchars($name) ?> o en cualquier otra ciudad, el catálogo es el mismo y los vendedores llegan hasta donde estés.
        </p>
        <div class="flex flex-wrap justify-center gap-2 text-xs mb-8">
          <?php foreach ($otherCities as $s => $c): ?>
          <a href="/ciudad/<?= htmlspecialchars($s) ?>"
             class="bg-white/10 hover:bg-white/20 text-white/60 hover:text-white px-3 py-1.5 rounded-full transition">
            <?= htmlspecialchars($c['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
        <a href="/"
           class="inline-flex items-center gap-2 bg-usared hover:bg-red-700 text-white font-bold text-sm px-7 py-3.5 rounded-xl transition">
          Ver el catálogo completo
        </a>
      </div>
    </section>

    <!-- ── CTA vendedores ─────────────────────────────────────────── -->
    <section class="bg-usalight">
      <div class="max-w-2xl mx-auto px-5 py-14 text-center">
        <h2 class="text-2xl font-bold text-navy mb-3">
          ¿Tienes importados de USA en <?= htmlspecialchars($name) ?>?
        </h2>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
          Si eres vendedor en <?= htmlspecialchars($name) ?>, <?= htmlspecialchars($dep) ?> y tienes productos originales de Estados Unidos,
          publica en el marketplace y llega a compradores en toda Colombia. Gratis. Sin comisiones.
        </p>
        <a href="/vende"
           class="inline-flex items-center gap-2 bg-navy hover:bg-navy/90 text-white font-semibold text-sm px-7 py-3.5 rounded-xl transition">
          Conoce los requisitos para vender →
        </a>
      </div>
    </section>

  </main>

  <?php include __DIR__ . '/_footer.php'; ?>

</body>
</html>
