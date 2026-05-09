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
    .cart-badge { animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }
    .product-card { transition: transform 0.2s; cursor: pointer; }
    .product-card:active { transform: scale(0.97); }
    .fade-in { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .category-pill { scroll-snap-align: start; }
    .categories-scroll { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .slide-up { animation: slideUp 0.35s ease; }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .card-slides { transition: transform 0.5s ease; }
    .modal-main-img { transition: opacity 0.25s ease; }
  </style>
</head>
<body class="h-full bg-white font-body text-navy overflow-auto">

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

    <!-- Categorías -->
    <section class="pt-4 pb-2 px-4">
      <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wide mb-2">Categorías</h3>
      <div class="categories-scroll flex gap-2 overflow-x-auto pb-1" id="categories-bar">
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

    <!-- Marcas -->
    <section class="pb-3 px-4">
      <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wide mb-2">Marcas</h3>
      <div class="categories-scroll flex gap-2 overflow-x-auto pb-1" id="brands-bar">
        <!-- generado por JS -->
      </div>
    </section>

    <!-- Título grid -->
    <section class="px-4 pb-2">
      <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg">🔥 Productos</h3>
        <span class="text-xs text-usared font-semibold">Importados USA</span>
      </div>
    </section>

    <!-- Grid de productos -->
    <section class="px-4 pb-24">
      <div class="grid grid-cols-2 gap-3" id="products-container">
        <?php foreach ($products as $p):
          $hasImg   = !empty($p['images']);
          $emoji    = $categoryEmoji[$p['category']] ?? '📦';
          $discount = $p['discount'];
          $showBadge = $discount >= 10;
        ?>
        <div
          class="product-card bg-white border border-gray-100 rounded-2xl p-3 shadow-sm relative fade-in"
          data-category="<?= htmlspecialchars($p['category']) ?>"
          data-brand="<?= htmlspecialchars($p['brand']) ?>"
          data-id="<?= $p['id'] ?>"
          onclick="openModal(<?= $p['id'] ?>)">

          <!-- Badge Apple -->
          <?php if ($p['brand'] === 'Apple'): ?>
          <div class="absolute top-2 left-2 z-10">
            <span class="bg-navy text-white text-[10px] font-bold px-2 py-0.5 rounded-full">🍎 Apple</span>
          </div>
          <?php endif; ?>

          <!-- Badge descuento -->
          <?php if ($showBadge): ?>
          <div class="absolute top-2 right-2 z-10">
            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">−<?= $discount ?>%</span>
          </div>
          <?php endif; ?>

          <!-- Carrusel de imágenes -->
          <div class="card-img-wrap relative w-full aspect-square rounded-xl overflow-hidden mb-2 bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="card-slides flex h-full w-full">
              <?php if ($hasImg): ?>
                <?php foreach ($p['images'] as $imgSrc): ?>
                <img
                  src="<?= htmlspecialchars($imgSrc) ?>"
                  alt="<?= htmlspecialchars($p['name']) ?>"
                  class="flex-shrink-0 w-full h-full object-cover"
                  loading="lazy">
                <?php endforeach; ?>
              <?php else: ?>
                <div class="flex-shrink-0 w-full h-full flex items-center justify-center text-4xl">
                  <?= $emoji ?>
                </div>
              <?php endif; ?>
            </div>
            <?php if (count($p['images']) > 1): ?>
            <div class="absolute bottom-1.5 left-0 right-0 flex justify-center gap-1 pointer-events-none">
              <?php foreach ($p['images'] as $i => $_): ?>
              <span class="slide-dot w-1.5 h-1.5 rounded-full <?= $i === 0 ? 'bg-white' : 'bg-white/40' ?>"></span>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <h4 class="font-semibold text-xs leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></h4>

          <?php if ($p['market_price'] !== null && $p['market_price'] > $p['price']): ?>
          <p class="text-gray-400 text-[11px] line-through leading-none mb-0.5"><?= fmt($p['market_price']) ?></p>
          <?php endif; ?>

          <p class="text-usared font-bold text-sm mb-2"><?= fmt($p['price']) ?></p>

          <button
            onclick="addToCart(<?= $p['id'] ?>); event.stopPropagation();"
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
        <p class="text-center text-gray-400 py-8">Tu carrito está vacío</p>
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

  <!-- ── Modal detalle de producto ────────────────── -->
  <div id="product-modal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/60" onclick="closeModal()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[92%] flex flex-col slide-up">

      <!-- Cabecera modal -->
      <div class="flex items-center justify-between px-4 pt-4 pb-2 flex-shrink-0">
        <span id="modal-category-badge" class="text-xs font-semibold text-gray-400 uppercase tracking-wide"></span>
        <button onclick="closeModal()" class="p-2 bg-gray-100 rounded-full">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Galería principal -->
      <div class="px-4 flex-shrink-0">
        <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-50">
          <img id="modal-main-img" src="" alt="" class="modal-main-img w-full h-full object-cover">
          <div id="modal-emoji-fallback" class="hidden w-full h-full items-center justify-center text-6xl absolute inset-0 bg-gray-50">
          </div>
          <!-- Flechas -->
          <button id="modal-prev" onclick="modalNav(-1)"
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/30 text-white w-8 h-8 rounded-full flex items-center justify-center hidden">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
          </button>
          <button id="modal-next" onclick="modalNav(1)"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/30 text-white w-8 h-8 rounded-full flex items-center justify-center hidden">
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </button>
          <!-- Dots -->
          <div id="modal-dots" class="absolute bottom-2 left-0 right-0 flex justify-center gap-1.5 pointer-events-none"></div>
        </div>
        <!-- Thumbnails -->
        <div id="modal-thumbs" class="flex gap-2 mt-2 overflow-x-auto pb-1"></div>
      </div>

      <!-- Info del producto -->
      <div class="flex-1 overflow-auto px-4 pt-3 pb-6">
        <h2 id="modal-name" class="font-bold text-base leading-snug mb-1"></h2>
        <p id="modal-meta" class="text-xs text-gray-500 mb-3"></p>

        <div id="modal-prices" class="flex items-center gap-3 mb-4"></div>

        <p id="modal-desc" class="text-xs text-gray-600 leading-relaxed mb-5"></p>

        <button id="modal-add-btn"
          class="w-full bg-navy text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 active:bg-usablue transition">
          <i data-lucide="plus" class="w-4 h-4"></i> Agregar al carrito
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

</div><!-- /#app -->

<!-- Datos de productos -->
<script>
window.catalogProducts = <?= json_encode($products, JSON_UNESCAPED_UNICODE) ?>;
</script>

<script>
const WHATSAPP_NUMBER = '17865683345';

let cart          = [];
let activeCategory = 'all';
let activeBrand    = 'all';

// ── Formato de precio ────────────────────────────
function fmt(n) {
  return '$' + Number(n).toLocaleString('es-CO');
}

// ── Emoji por categoría ──────────────────────────
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
      dots.forEach((d, i) => {
        d.style.opacity = i === idx ? '1' : '0.4';
      });
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
  document.querySelectorAll('[data-cat]').forEach(btn => {
    const on = btn.dataset.cat === id;
    btn.classList.toggle('bg-usared',   on);
    btn.classList.toggle('text-white',  on);
    btn.classList.toggle('bg-gray-100', !on);
    btn.classList.toggle('text-navy',   !on);
  });
  applyFilters();
}

function filterBrand(brand) {
  activeBrand = brand;
  document.querySelectorAll('[data-brand-btn]').forEach(btn => {
    const on = btn.dataset.brandBtn === brand;
    btn.classList.toggle('bg-usablue',  on);
    btn.classList.toggle('text-white',  on);
    btn.classList.toggle('bg-gray-100', !on);
    btn.classList.toggle('text-navy',   !on);
  });
  applyFilters();
}

function buildBrandsBar() {
  const raw    = window.catalogProducts.map(p => p.brand).filter(Boolean);
  const brands = ['all', ...[...new Set(raw)].sort()];
  const bar    = document.getElementById('brands-bar');
  bar.innerHTML = brands.map(b => {
    const isAll = b === 'all';
    const on    = isAll;
    return `<button
      onclick="filterBrand('${b.replace(/'/g, "\\'")}')"
      data-brand-btn="${b.replace(/"/g, '&quot;')}"
      class="category-pill flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition whitespace-nowrap
             ${on ? 'bg-usablue text-white' : 'bg-gray-100 text-navy'}">
      ${isAll ? '🏷️ Todas' : b}
    </button>`;
  }).join('');
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
  let msg = '¡Hola! 👋 Me interesan estos productos:\n\n';
  cart.forEach(item => { msg += `• ${item.name} x${item.qty} — ${fmt(item.price * item.qty)}\n`; });
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

  // Categoría
  document.getElementById('modal-category-badge').textContent = p.brand || '';

  // Nombre
  document.getElementById('modal-name').textContent = p.name;

  // Meta (marca · capacidad)
  const parts = [p.brand, p.capacity].filter(Boolean);
  document.getElementById('modal-meta').textContent = parts.join(' · ');

  // Precios
  const pricesEl = document.getElementById('modal-prices');
  let pricesHtml = `<span class="text-usared font-bold text-xl">${fmt(p.price)}</span>`;
  if (p.market_price && p.market_price > p.price) {
    pricesHtml += `<span class="text-gray-400 text-sm line-through">${fmt(p.market_price)}</span>`;
    if (p.discount >= 5) {
      pricesHtml += `<span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">−${p.discount}%</span>`;
    }
  }
  pricesEl.innerHTML = pricesHtml;

  // Descripción
  document.getElementById('modal-desc').textContent = p.description || '';

  // Galería
  renderModalGallery();

  // Botón agregar
  const addBtn = document.getElementById('modal-add-btn');
  addBtn.onclick = () => {
    const fakeEvent = { target: addBtn };
    const origEvent = window.event;
    // Simula addToCart sin el event.stopPropagation problemático
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
    }, 1000);
  };

  document.getElementById('product-modal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  lucide.createIcons();
}

function renderModalGallery() {
  const p      = currentProduct;
  const hasImg = p.images && p.images.length > 0;

  // Imagen principal
  const mainImg    = document.getElementById('modal-main-img');
  const emojiFall  = document.getElementById('modal-emoji-fallback');

  if (hasImg) {
    mainImg.src = p.images[modalImgIdx];
    mainImg.alt = p.name;
    mainImg.classList.remove('hidden');
    emojiFall.classList.add('hidden');
    emojiFall.classList.remove('flex');
  } else {
    mainImg.classList.add('hidden');
    emojiFall.innerHTML = getEmoji(p.category);
    emojiFall.classList.remove('hidden');
    emojiFall.classList.add('flex');
  }

  // Flechas
  const showArrows = hasImg && p.images.length > 1;
  document.getElementById('modal-prev').classList.toggle('hidden', !showArrows);
  document.getElementById('modal-next').classList.toggle('hidden', !showArrows);

  // Dots
  const dotsEl = document.getElementById('modal-dots');
  if (hasImg && p.images.length > 1) {
    dotsEl.innerHTML = p.images.map((_, i) =>
      `<span class="w-2 h-2 rounded-full ${i === modalImgIdx ? 'bg-white' : 'bg-white/40'}"></span>`
    ).join('');
  } else {
    dotsEl.innerHTML = '';
  }

  // Thumbnails
  const thumbsEl = document.getElementById('modal-thumbs');
  if (hasImg && p.images.length > 1) {
    thumbsEl.innerHTML = p.images.map((src, i) => `
      <button onclick="setModalImg(${i})"
        class="flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden border-2 transition
               ${i === modalImgIdx ? 'border-usared' : 'border-transparent opacity-60'}">
        <img src="${src}" class="w-full h-full object-cover" loading="lazy">
      </button>
    `).join('');
  } else {
    thumbsEl.innerHTML = '';
  }
}

function setModalImg(idx) {
  modalImgIdx = idx;
  renderModalGallery();
}

function modalNav(dir) {
  const len = currentProduct.images.length;
  modalImgIdx = (modalImgIdx + dir + len) % len;
  renderModalGallery();
}

function closeModal() {
  document.getElementById('product-modal').classList.add('hidden');
  document.body.style.overflow = '';
  currentProduct = null;
}

// ── Init ─────────────────────────────────────────
buildBrandsBar();
initCardSlideshows();
lucide.createIcons();
</script>
</body>
</html>
