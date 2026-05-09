<!doctype html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>USA Imports</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy:    '#0A1628',
            usared:  '#B22234',
            usablue: '#3C3B6E',
            usalight:'#EDF2F7'
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
    .carousel-track { display: flex; animation: scroll 20s linear infinite; }
    @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    .cart-badge { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }
    .product-card { transition: transform 0.2s; }
    .product-card:active { transform: scale(0.97); }
    .fade-in { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .category-pill { scroll-snap-align: start; }
    .categories-scroll { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .slide-up { animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .img-gallery { display: flex; gap: 6px; overflow-x: auto; scroll-snap-type: x mandatory; }
    .img-gallery img { scroll-snap-align: start; width: 100%; height: 100%; object-fit: cover; flex-shrink: 0; border-radius: 0.75rem; }
  </style>
</head>
<body class="h-full bg-white font-body text-navy overflow-auto">

<?php
/* ── helpers ─────────────────────────────────────── */
function fmt(int $n): string {
    return '$' . number_format($n, 0, ',', '.');
}

$categoryEmoji = [
    'celulares'  => '📱',
    'audifonos'  => '🎧',
    'tablets'    => '📲',
    'consolas'   => '🎮',
    'parlantes'  => '🔊',
    'cargadores' => '🔋',
    'camaras'    => '📷',
    'iluminacion'=> '💡',
    'gafas'      => '🕶️',
    'apple'      => '🍎',
    'otros'      => '📦',
];
?>

<div id="app" class="h-full w-full flex flex-col">

  <!-- ── Header ───────────────────────────────────── -->
  <header class="sticky top-0 z-50 bg-navy text-white px-4 py-3 flex items-center justify-between shadow-lg">
    <div class="flex items-center gap-2">
      <span class="text-2xl">🇺🇸</span>
      <h1 class="font-display text-xl tracking-wide">USA IMPORTS</h1>
    </div>
    <button id="cart-btn" class="relative p-2" onclick="toggleCart()">
      <i data-lucide="shopping-bag" class="w-6 h-6"></i>
      <span id="cart-count" class="absolute -top-1 -right-1 bg-usared text-white text-xs w-5 h-5 rounded-full flex items-center justify-center cart-badge hidden">0</span>
    </button>
  </header>

  <!-- ── Main ─────────────────────────────────────── -->
  <main class="flex-1 overflow-auto" id="main-content">

    <!-- Hero -->
    <section class="bg-gradient-to-br from-usablue via-navy to-usablue text-white px-5 py-8 text-center">
      <h2 class="font-display text-4xl mb-2">DIRECTO DESDE USA 🇺🇸</h2>
      <p class="text-sm opacity-80 mb-4">Productos originales importados · Envío a todo el país</p>
      <div class="flex justify-center gap-3">
        <span class="bg-usared/90 text-xs font-semibold px-3 py-1 rounded-full">✓ Originales</span>
        <span class="bg-white/20 text-xs font-semibold px-3 py-1 rounded-full">✓ Garantía</span>
        <span class="bg-white/20 text-xs font-semibold px-3 py-1 rounded-full">✓ Envíos</span>
      </div>
    </section>

    <!-- Carousel decorativo -->
    <section class="overflow-hidden bg-gray-100 py-3">
      <div class="carousel-track">
        <?php
        $tiles = [
            ['from-usablue','to-blue-400','📱'],
            ['from-red-500','to-pink-400','🎧'],
            ['from-green-500','to-emerald-400','🎮'],
            ['from-yellow-400','to-orange-400','🔊'],
            ['from-purple-500','to-indigo-400','📷'],
            ['from-blue-400','to-cyan-300','🕶️'],
            ['from-gray-600','to-gray-800','🧳'],
            ['from-pink-400','to-red-400','💡'],
        ];
        // Duplicate for seamless loop
        for ($pass = 0; $pass < 2; $pass++): ?>
        <div class="flex gap-3 px-3">
          <?php foreach ($tiles as [$from, $to, $emoji]): ?>
          <div class="w-28 h-28 rounded-xl bg-gradient-to-br <?= $from ?> <?= $to ?> flex items-center justify-center text-3xl flex-shrink-0">
            <?= $emoji ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endfor; ?>
      </div>
    </section>

    <!-- Categorías -->
    <section class="py-4 px-4">
      <h3 class="font-bold text-lg mb-3">Categorías</h3>
      <div class="categories-scroll flex gap-2 overflow-x-auto pb-2" id="categories-bar">
        <?php foreach ($categories as $cat): ?>
        <button
          onclick="filterCategory('<?= htmlspecialchars($cat['id']) ?>')"
          data-cat="<?= htmlspecialchars($cat['id']) ?>"
          class="category-pill flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold transition whitespace-nowrap <?= $cat['id'] === 'all' ? 'bg-usared text-white' : 'bg-gray-100 text-navy' ?>">
          <?= $cat['emoji'] ?> <?= htmlspecialchars($cat['name']) ?>
        </button>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Sección destacados (título) -->
    <section class="px-4 pb-2">
      <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg">🔥 Productos</h3>
        <span class="text-xs text-usared font-semibold">Importados USA</span>
      </div>
    </section>

    <!-- Grid de productos -->
    <section class="px-4 pb-24" id="products-grid">
      <div class="grid grid-cols-2 gap-3" id="products-container">
        <?php foreach ($products as $p):
          $hasImg    = !empty($p['images']);
          $mainImg   = $hasImg ? $p['images'][0] : null;
          $emoji     = $categoryEmoji[$p['category']] ?? '📦';
          $discount  = $p['discount'];
          $showBadge = $discount >= 10;
        ?>
        <div
          class="product-card bg-white border border-gray-100 rounded-2xl p-3 shadow-sm relative fade-in"
          data-category="<?= htmlspecialchars($p['category']) ?>"
          data-id="<?= $p['id'] ?>">

          <!-- Badges -->
          <div class="absolute top-2 left-2 flex flex-col gap-1 z-10">
            <?php if ($p['brand'] === 'Apple'): ?>
            <span class="bg-navy text-white text-[10px] font-bold px-2 py-0.5 rounded-full">🍎 Apple</span>
            <?php endif; ?>
          </div>

          <?php if ($showBadge): ?>
          <div class="absolute top-2 right-2 z-10">
            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">−<?= $discount ?>%</span>
          </div>
          <?php endif; ?>

          <!-- Imagen o emoji -->
          <div class="w-full aspect-square rounded-xl overflow-hidden flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 mb-2">
            <?php if ($hasImg): ?>
            <img
              src="<?= htmlspecialchars($mainImg) ?>"
              alt="<?= htmlspecialchars($p['name']) ?>"
              class="w-full h-full object-cover"
              loading="lazy">
            <?php else: ?>
            <span class="text-4xl"><?= $emoji ?></span>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <h4 class="font-semibold text-xs leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h4>

          <?php if ($p['market_price'] !== null && $p['market_price'] > $p['price']): ?>
          <p class="text-gray-400 text-[11px] line-through leading-none mb-0.5"><?= fmt($p['market_price']) ?></p>
          <?php endif; ?>

          <p class="text-usared font-bold text-sm mb-2"><?= fmt($p['price']) ?></p>

          <button
            onclick="addToCart(<?= $p['id'] ?>)"
            class="w-full bg-navy text-white text-xs font-semibold py-2 rounded-lg flex items-center justify-center gap-1 active:bg-usablue transition">
            <i data-lucide="plus" class="w-3 h-3"></i> Agregar
          </button>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

  </main>

  <!-- ── Carrito overlay ──────────────────────────── -->
  <div id="cart-overlay" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="toggleCart()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl max-h-[80%] flex flex-col slide-up">
      <div class="flex items-center justify-between p-4 border-b">
        <h3 class="font-bold text-lg">Tu Carrito 🛒</h3>
        <button onclick="toggleCart()" class="p-1"><i data-lucide="x" class="w-5 h-5"></i></button>
      </div>
      <div id="cart-items" class="flex-1 overflow-auto p-4">
        <p id="cart-empty" class="text-center text-gray-400 py-8">Tu carrito está vacío</p>
      </div>
      <div id="cart-footer" class="p-4 border-t hidden">
        <div class="flex justify-between mb-3">
          <span class="font-semibold">Total:</span>
          <span id="cart-total" class="font-bold text-usared text-lg">$0</span>
        </div>
        <button onclick="sendWhatsApp()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
          </svg>
          Comprar por WhatsApp
        </button>
      </div>
    </div>
  </div>

  <!-- ── WhatsApp float ────────────────────────────── -->
  <a
    href="https://wa.me/17865683345?text=<?= urlencode('¡Hola! Vi sus productos importados de USA y me gustaría saber más 🇺🇸') ?>"
    target="_blank"
    rel="noopener noreferrer"
    class="fixed bottom-5 right-5 z-50 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition">
    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.625-1.467A11.932 11.932 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75c-2.17 0-4.207-.58-5.963-1.588l-.428-.254-2.742.87.885-2.666-.279-.442A9.722 9.722 0 012.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75z"/>
    </svg>
  </a>

</div>

<!-- Datos de productos para JS del carrito -->
<script>
window.catalogProducts = <?= json_encode($products, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script>
const WHATSAPP_NUMBER = '17865683345';

let cart = [];
let activeCategory = 'all';

function fmt(n) {
  return '$' + n.toLocaleString('es-CO');
}

// Filtro de categorías
function filterCategory(id) {
  activeCategory = id;

  // Actualizar pills
  document.querySelectorAll('[data-cat]').forEach(btn => {
    const active = btn.dataset.cat === id;
    btn.className = btn.className
      .replace(/bg-usared text-white|bg-gray-100 text-navy/g, '').trim();
    btn.classList.add(active ? 'bg-usared' : 'bg-gray-100');
    btn.classList.add(active ? 'text-white' : 'text-navy');
  });

  // Mostrar/ocultar tarjetas
  document.querySelectorAll('[data-category]').forEach(card => {
    const show = id === 'all' || card.dataset.category === id;
    card.style.display = show ? '' : 'none';
  });
}

// Carrito
function addToCart(productId) {
  const p = window.catalogProducts.find(x => x.id === productId);
  if (!p) return;

  const existing = cart.find(x => x.id === productId);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ ...p, qty: 1 });
  }

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
  if (count > 0) {
    badge.textContent = count;
    badge.classList.remove('hidden');
  } else {
    badge.classList.add('hidden');
  }
}

function toggleCart() {
  const overlay = document.getElementById('cart-overlay');
  overlay.classList.toggle('hidden');
  renderCart();
}

function renderCart() {
  const itemsEl  = document.getElementById('cart-items');
  const footerEl = document.getElementById('cart-footer');

  if (cart.length === 0) {
    itemsEl.innerHTML = '<p class="text-center text-gray-400 py-8">Tu carrito está vacío</p>';
    footerEl.classList.add('hidden');
    return;
  }

  footerEl.classList.remove('hidden');
  itemsEl.innerHTML = cart.map(item => `
    <div class="flex items-center gap-3 py-3 border-b">
      <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
        ${item.images && item.images[0]
          ? `<img src="${item.images[0]}" class="w-full h-full object-cover" alt="">`
          : `<span class="text-xl">${getEmoji(item.category)}</span>`}
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold truncate">${item.name}</p>
        <p class="text-xs text-usared font-bold">${fmt(item.price)}</p>
      </div>
      <div class="flex items-center gap-2">
        <button onclick="changeQty(${item.id}, -1)" class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-sm font-bold">−</button>
        <span class="text-sm font-semibold w-4 text-center">${item.qty}</span>
        <button onclick="changeQty(${item.id}, 1)" class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-sm font-bold">+</button>
      </div>
    </div>
  `).join('');

  const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
  document.getElementById('cart-total').textContent = fmt(total);
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
  let msg = '¡Hola! 👋 Me interesan estos productos:\n\n';
  cart.forEach(item => {
    msg += `• ${item.name} x${item.qty} — ${fmt(item.price * item.qty)}\n`;
  });
  const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
  msg += `\n💰 Total: ${fmt(total)}\n\n¿Están disponibles?`;
  window.open(`https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(msg)}`, '_blank');
}

function getEmoji(category) {
  const map = {
    celulares: '📱', audifonos: '🎧', tablets: '📲',
    consolas: '🎮', parlantes: '🔊', cargadores: '🔋',
    camaras: '📷', iluminacion: '💡', gafas: '🕶️',
    apple: '🍎', otros: '📦'
  };
  return map[category] || '📦';
}

lucide.createIcons();
</script>
</body>
</html>
