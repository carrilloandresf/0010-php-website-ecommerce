<?php
declare(strict_types=1);
session_start();

define('ADMIN_PASSWORD', 'Flor1234**');
define('DATA_PATH', dirname(__DIR__, 2) . '/data/inventory.json');
define('IMG_DIR',   dirname(__DIR__) . '/public/img/');
define('IMG_WEB',   '/img/');

// ── Helpers ───────────────────────────────────────────────────────────────────

function readInventory(): array {
    $raw = file_get_contents(DATA_PATH);
    return json_decode($raw, true) ?? [];
}

function writeInventory(array $data): bool {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return (bool) file_put_contents(DATA_PATH, $json, LOCK_EX);
}

function makeSlug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $map  = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n','ä'=>'a','ö'=>'o'];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/', '_', $text);
    return trim($text, '_');
}

function getProductImages(int $indice): array {
    $files = glob(IMG_DIR . $indice . '[_.-]*') ?: [];
    usort($files, function (string $a, string $b) use ($indice): int {
        preg_match('/^' . $indice . '[_.\-](\d+)/i', basename($a), $ma);
        preg_match('/^' . $indice . '[_.\-](\d+)/i', basename($b), $mb);
        return (int)($ma[1] ?? 0) <=> (int)($mb[1] ?? 0);
    });
    return array_map('basename', $files);
}

function nextImageSeq(int $indice): int {
    $files = glob(IMG_DIR . $indice . '[_.-]*') ?: [];
    $max   = 0;
    foreach ($files as $f) {
        if (preg_match('/^' . $indice . '[_.\-](\d+)/i', basename($f), $m)) {
            $max = max($max, (int)$m[1]);
        }
    }
    return $max + 1;
}

function writeErrorParams(string $path): string {
    $writable = is_writable($path) ? 'sí' : 'NO';
    $owner    = function_exists('posix_getpwuid') ? (posix_getpwuid(fileowner($path))['name'] ?? '?') : '?';
    $process  = function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? '?') : '?';
    return '?write_error=1'
        . '&file='     . urlencode($path)
        . '&writable=' . urlencode($writable)
        . '&owner='    . urlencode($owner)
        . '&process='  . urlencode($process);
}

// ── Actions ───────────────────────────────────────────────────────────────────

$action = $_GET['action'] ?? '';

// Logout
if ($action === 'logout') {
    session_destroy();
    header('Location: /admin/panel');
    exit;
}

// Auth check
if (empty($_SESSION['admin_auth'])) {
    $loginError = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === ADMIN_PASSWORD) {
            session_regenerate_id(true);
            $_SESSION['admin_auth'] = true;
            header('Location: /admin/panel');
            exit;
        }
        $loginError = 'Contraseña incorrecta.';
    }
    ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — FromUSA</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script>tailwind.config={theme:{extend:{colors:{navy:'#0A1628',usared:'#B22234'}}}}</script>
  <meta name="robots" content="noindex,nofollow">
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
  <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg p-8">
    <div class="text-center mb-8">
      <div class="text-3xl font-black text-navy mb-1" style="font-family:'Bebas Neue',sans-serif;letter-spacing:1px">FROMUSA</div>
      <p class="text-sm text-gray-500">Panel de administración</p>
    </div>
    <?php if ($loginError): ?>
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
        <?= htmlspecialchars($loginError) ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="/admin/panel">
      <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
      <input type="password" name="password" autofocus
        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy mb-4"
        placeholder="••••••••">
      <button type="submit"
        class="w-full bg-navy text-white font-bold py-3 rounded-xl hover:bg-opacity-90 transition text-sm">
        Ingresar
      </button>
    </form>
  </div>
</body>
</html>
    <?php
    exit;
}

// ── Acciones autenticadas ─────────────────────────────────────────────────────

$savedId    = isset($_GET['saved'])    ? (int)$_GET['saved'] : 0;
$writeError = !empty($_GET['write_error']);
$errorInfo  = $writeError ? [
    'file'     => $_GET['file']     ?? DATA_PATH,
    'writable' => $_GET['writable'] ?? '?',
    'owner'    => $_GET['owner']    ?? '?',
    'process'  => $_GET['process']  ?? '?',
] : [];

$editableFields = [
    'nombre_web'         => ['label' => 'Nombre web',         'type' => 'text',     'required' => true],
    'marca'              => ['label' => 'Marca',               'type' => 'text',     'required' => false],
    'modelo'             => ['label' => 'Modelo',              'type' => 'text',     'required' => false],
    'categoria'          => ['label' => 'Categoría',           'type' => 'text',     'required' => false],
    'capacidad'          => ['label' => 'Capacidad',           'type' => 'text',     'required' => false],
    'precio_venta_cop'   => ['label' => 'Precio venta (COP)',  'type' => 'number',   'required' => false],
    'precio_mercado_cop' => ['label' => 'Precio mercado (COP)','type' => 'number',   'required' => false],
    'precio_usd'         => ['label' => 'Precio USD',          'type' => 'number',   'required' => false],
    'cantidad'           => ['label' => 'Stock',               'type' => 'number',   'required' => false],
    'descripcion'        => ['label' => 'Descripción',         'type' => 'textarea', 'required' => false],
];

// ADD PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $data = readInventory();

    $maxIndice = 0;
    foreach ($data['productos'] as $p) {
        $maxIndice = max($maxIndice, (int)($p['indice'] ?? 0));
    }
    $newIndice = $maxIndice + 1;

    $nombreWeb = trim($_POST['nombre_web'] ?? 'Nuevo producto');
    $baseSlug  = makeSlug($nombreWeb) ?: 'producto_' . $newIndice;
    $slug      = $baseSlug;
    $i         = 2;
    while (isset($data['productos'][$slug])) {
        $slug = $baseSlug . '_' . $i++;
    }

    $newProduct = ['indice' => $newIndice, 'slug' => $slug];
    foreach ($editableFields as $field => $meta) {
        $val = trim($_POST[$field] ?? '');
        if (in_array($field, ['precio_venta_cop', 'precio_mercado_cop', 'cantidad'], true)) {
            $newProduct[$field] = $val !== '' ? (int)$val : 0;
        } elseif ($field === 'precio_usd') {
            $newProduct[$field] = $val !== '' ? (float)$val : null;
        } else {
            $newProduct[$field] = $val !== '' ? $val : null;
        }
    }
    $newProduct['precio_web'] = $newProduct['precio_venta_cop'] ?? 0;

    $data['productos'][$slug]    = $newProduct;
    $data['orden_productos'][]   = $slug;

    if (writeInventory($data)) {
        header('Location: /admin/panel?saved=' . $newIndice . '#prod-' . $newIndice);
    } else {
        header('Location: /admin/panel' . writeErrorParams(DATA_PATH));
    }
    exit;
}

// UPDATE PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $id   = (int)($_GET['id'] ?? 0);
    $data = readInventory();
    foreach ($data['productos'] as $key => &$p) {
        if ((int)$p['indice'] === $id) {
            foreach ($editableFields as $field => $meta) {
                if (!isset($_POST[$field])) continue;
                $val = trim($_POST[$field]);
                if (in_array($field, ['precio_venta_cop', 'precio_mercado_cop', 'cantidad'], true)) {
                    $p[$field] = $val !== '' ? (int)$val : null;
                } elseif ($field === 'precio_usd') {
                    $p[$field] = $val !== '' ? (float)$val : null;
                } else {
                    $p[$field] = $val !== '' ? $val : null;
                }
            }
            if (isset($p['precio_venta_cop'])) {
                $p['precio_web'] = $p['precio_venta_cop'];
            }
            break;
        }
    }
    unset($p);

    if (writeInventory($data)) {
        header('Location: /admin/panel?saved=' . $id . '#prod-' . $id);
    } else {
        header('Location: /admin/panel' . writeErrorParams(DATA_PATH) . '#prod-' . $id);
    }
    exit;
}

// UPLOAD IMAGE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0 && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed, true) && $_FILES['image']['size'] <= 20 * 1024 * 1024) {
            $seq  = nextImageSeq($id);
            $dest = IMG_DIR . $id . '_' . $seq . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        }
    }
    header('Location: /admin/panel?saved=' . $id . '#prod-' . $id);
    exit;
}

// DELETE IMAGE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete_image') {
    $id       = (int)($_GET['id'] ?? 0);
    $filename = basename($_POST['filename'] ?? '');
    if ($id > 0 && $filename && preg_match('/^' . $id . '[_.\-]/i', $filename)) {
        $fullPath = IMG_DIR . $filename;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    header('Location: /admin/panel?saved=' . $id . '#prod-' . $id);
    exit;
}

// ── Cargar datos del dashboard ────────────────────────────────────────────────

$data       = readInventory();
$products   = [];
$categories = [];
foreach (($data['orden_productos'] ?? array_keys($data['productos'] ?? [])) as $key) {
    if (!isset($data['productos'][$key])) continue;
    $p            = $data['productos'][$key];
    $p['_images'] = getProductImages((int)$p['indice']);
    $products[]   = $p;
    $cat = strtolower(trim($p['categoria'] ?? ''));
    if ($cat) $categories[$cat] = true;
}
ksort($categories);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Panel — FromUSA</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script>
    tailwind.config = {
      theme: { extend: { colors: { navy: '#0A1628', usared: '#B22234', usablue: '#3C3B6E' } } }
    }
  </script>
  <meta name="robots" content="noindex,nofollow">
  <style>
    details > summary { list-style: none; }
    details > summary::-webkit-details-marker { display: none; }
    details[open] .chevron { transform: rotate(180deg); }
    .chevron { transition: transform 0.2s; }
    .prod-row.hidden-filter { display: none; }
    #new-product-form { display: none; }
    #new-product-form.open { display: block; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Header -->
<header class="bg-navy text-white px-6 py-4 flex items-center justify-between sticky top-0 z-50 shadow-lg">
  <div class="font-black text-xl tracking-widest" style="font-family:'Bebas Neue',sans-serif">
    FROMUSA <span class="text-xs font-normal tracking-normal opacity-60 ml-2">ADMIN</span>
  </div>
  <div class="flex items-center gap-3">
    <a href="/" target="_blank" class="text-xs bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg transition hidden sm:block">Ver sitio →</a>
    <a href="/admin/panel?action=logout" class="text-xs bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">Cerrar sesión</a>
  </div>
</header>

<!-- Flash éxito -->
<?php if ($savedId && !$writeError): ?>
<div id="flash" class="bg-green-50 border-b border-green-200 text-green-700 text-sm px-6 py-3 flex items-center gap-2">
  <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
  </svg>
  Cambios guardados correctamente (producto #<?= $savedId ?>).
</div>
<script>setTimeout(() => { const f = document.getElementById('flash'); if (f) f.remove(); }, 4000);</script>
<?php endif; ?>

<!-- Flash error de escritura -->
<?php if ($writeError): ?>
<div class="bg-red-50 border-b border-red-300 text-red-800 text-sm px-6 py-4">
  <p class="font-bold mb-1">⚠️ Error: no se pudo guardar el archivo JSON.</p>
  <p class="mb-2">El proceso PHP no tiene permiso de escritura sobre el inventario.</p>
  <div class="font-mono text-xs bg-red-100 rounded p-3 space-y-1">
    <div><strong>Archivo:</strong> <?= htmlspecialchars($errorInfo['file']) ?></div>
    <div><strong>¿Escribible?:</strong> <?= htmlspecialchars($errorInfo['writable']) ?></div>
    <div><strong>Dueño del archivo:</strong> <?= htmlspecialchars($errorInfo['owner']) ?></div>
    <div><strong>Proceso PHP corre como:</strong> <?= htmlspecialchars($errorInfo['process']) ?></div>
  </div>
  <p class="mt-2 text-xs">Solución en el servidor:
    <code class="bg-red-100 px-1 rounded">chmod 664 data/inventory.json && chown <?= htmlspecialchars($errorInfo['process']) ?>:<?= htmlspecialchars($errorInfo['process']) ?> data/inventory.json</code>
  </p>
</div>
<?php endif; ?>

<!-- Contenido principal -->
<main class="max-w-5xl mx-auto px-4 py-6">

  <!-- Barra superior: título + botón nuevo -->
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-navy">
      Productos
      <span id="count-label" class="text-base font-normal text-gray-400">(<?= count($products) ?> en catálogo)</span>
    </h1>
    <button onclick="toggleNewForm()"
      class="flex items-center gap-2 bg-usared text-white font-bold text-sm px-4 py-2.5 rounded-xl hover:bg-red-700 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
      </svg>
      Nuevo producto
    </button>
  </div>

  <!-- Formulario nuevo producto -->
  <div id="new-product-form" class="bg-white border-2 border-usared/30 rounded-2xl shadow-sm mb-5 overflow-hidden">
    <div class="bg-usared/5 px-5 py-3 border-b border-usared/20 flex items-center justify-between">
      <h2 class="font-bold text-sm text-usared">Agregar nuevo producto</h2>
      <button type="button" onclick="toggleNewForm()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">×</button>
    </div>
    <form method="POST" action="/admin/panel?action=add" class="p-5">
      <div class="grid sm:grid-cols-2 gap-4">
        <?php foreach ($editableFields as $field => $meta): ?>
        <div <?= $meta['type'] === 'textarea' ? 'class="sm:col-span-2"' : '' ?>>
          <label class="block text-xs font-semibold text-gray-500 mb-1">
            <?= $meta['label'] ?><?= $meta['required'] ? ' <span class="text-usared">*</span>' : '' ?>
          </label>
          <?php if ($meta['type'] === 'textarea'): ?>
            <textarea name="<?= $field ?>" rows="2" placeholder="Descripción del producto..."
              class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-usared resize-none"></textarea>
          <?php else: ?>
            <input type="<?= $meta['type'] ?>" name="<?= $field ?>"
              <?= $meta['required'] ? 'required' : '' ?>
              <?= $meta['type'] === 'number' ? 'step="any" min="0"' : '' ?>
              placeholder="<?= htmlspecialchars($meta['label']) ?>"
              class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-usared">
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="mt-5 flex gap-3">
        <button type="submit"
          class="flex-1 bg-usared text-white font-bold py-2.5 rounded-xl hover:bg-red-700 transition text-sm">
          Crear producto
        </button>
        <button type="button" onclick="toggleNewForm()"
          class="px-5 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:border-gray-300 transition text-sm">
          Cancelar
        </button>
      </div>
    </form>
  </div>

  <!-- Barra de filtros -->
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3 mb-4 flex flex-wrap gap-3 items-center">
    <!-- Búsqueda -->
    <div class="relative flex-1 min-w-[180px]">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
      </svg>
      <input id="filter-search" type="search" placeholder="Buscar por nombre o marca..."
        oninput="applyFilters()"
        class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-navy">
    </div>
    <!-- Categoría -->
    <select id="filter-cat" onchange="applyFilters()"
      class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy bg-white">
      <option value="">Todas las categorías</option>
      <?php foreach (array_keys($categories) as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars(ucfirst($cat)) ?></option>
      <?php endforeach; ?>
    </select>
    <!-- Stock -->
    <select id="filter-stock" onchange="applyFilters()"
      class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy bg-white">
      <option value="">Todo el stock</option>
      <option value="in">En stock</option>
      <option value="out">Sin stock</option>
    </select>
    <!-- Reset -->
    <button onclick="resetFilters()"
      class="text-xs text-gray-400 hover:text-gray-600 px-2 py-2 transition">
      Limpiar
    </button>
    <!-- Contador visible -->
    <span id="filter-count" class="text-xs text-gray-400 ml-auto"></span>
  </div>

  <!-- Lista de productos -->
  <div id="products-list" class="space-y-3">
  <?php foreach ($products as $p):
    $indice   = (int)$p['indice'];
    $isOpen   = $savedId === $indice;
    $images   = $p['_images'];
    $precio   = isset($p['precio_venta_cop']) ? '$' . number_format((int)$p['precio_venta_cop'], 0, ',', '.') : '—';
    $stock    = (int)($p['cantidad'] ?? 0);
    $catLower = strtolower(trim($p['categoria'] ?? ''));
    $nameLower = strtolower($p['nombre_web'] ?? '');
    $brandLower = strtolower($p['marca'] ?? '');
  ?>
  <details id="prod-<?= $indice ?>"
    class="prod-row bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
    <?= $isOpen ? 'open' : '' ?>
    data-name="<?= htmlspecialchars($nameLower) ?>"
    data-brand="<?= htmlspecialchars($brandLower) ?>"
    data-cat="<?= htmlspecialchars($catLower) ?>"
    data-stock="<?= $stock > 0 ? 'in' : 'out' ?>">

    <!-- Fila resumen -->
    <summary class="flex items-center gap-4 px-5 py-4 cursor-pointer hover:bg-gray-50 transition select-none">
      <div class="w-12 h-12 flex-shrink-0 rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
        <?php if (!empty($images)): ?>
          <img src="<?= IMG_WEB . htmlspecialchars($images[0]) ?>" class="w-full h-full object-contain" loading="lazy" alt="">
        <?php else: ?>
          <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
        <?php endif; ?>
      </div>
      <div class="flex-1 min-w-0">
        <div class="font-semibold text-sm truncate"><?= htmlspecialchars($p['nombre_web'] ?? '') ?></div>
        <div class="text-xs text-gray-400"><?= htmlspecialchars($p['marca'] ?? '') ?><?= ($p['marca'] ?? '') && ($p['categoria'] ?? '') ? ' · ' : '' ?><?= htmlspecialchars($p['categoria'] ?? '') ?></div>
      </div>
      <div class="text-right flex-shrink-0 hidden sm:block">
        <div class="font-bold text-usared text-sm"><?= $precio ?></div>
        <div class="text-xs <?= $stock > 0 ? 'text-green-600' : 'text-red-500' ?>">
          <?= $stock > 0 ? $stock . ' en stock' : 'Sin stock' ?>
        </div>
      </div>
      <div class="text-xs text-gray-300 hidden md:block flex-shrink-0">#<?= $indice ?></div>
      <svg class="chevron w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </summary>

    <!-- Panel expandido -->
    <div class="border-t border-gray-100 px-5 py-6">
      <div class="grid md:grid-cols-2 gap-8">

        <!-- Datos del producto -->
        <div>
          <h3 class="font-semibold text-sm text-gray-700 mb-4">Datos del producto</h3>
          <form method="POST" action="/admin/panel?action=update&id=<?= $indice ?>">
            <div class="space-y-3">
            <?php foreach ($editableFields as $field => $meta): ?>
              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1"><?= $meta['label'] ?></label>
                <?php if ($meta['type'] === 'textarea'): ?>
                  <textarea name="<?= $field ?>" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy resize-none"
                  ><?= htmlspecialchars((string)($p[$field] ?? '')) ?></textarea>
                <?php else: ?>
                  <input type="<?= $meta['type'] ?>" name="<?= $field ?>"
                    value="<?= htmlspecialchars((string)($p[$field] ?? '')) ?>"
                    <?= $meta['type'] === 'number' ? 'step="any"' : '' ?>
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
            </div>
            <button type="submit"
              class="mt-5 w-full bg-navy text-white font-bold py-2.5 rounded-xl hover:bg-opacity-90 transition text-sm">
              Guardar cambios
            </button>
          </form>
        </div>

        <!-- Imágenes -->
        <div>
          <h3 class="font-semibold text-sm text-gray-700 mb-4">
            Imágenes <span class="text-gray-400 font-normal">(<?= count($images) ?>)</span>
          </h3>

          <?php if (!empty($images)): ?>
          <div class="grid grid-cols-3 gap-2 mb-5">
            <?php foreach ($images as $imgFile): ?>
            <div class="relative group">
              <div class="aspect-square rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                <img src="<?= IMG_WEB . htmlspecialchars($imgFile) ?>" class="w-full h-full object-contain" loading="lazy" alt="">
              </div>
              <form method="POST" action="/admin/panel?action=delete_image&id=<?= $indice ?>"
                class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition"
                onsubmit="return confirm('¿Eliminar esta imagen?')">
                <input type="hidden" name="filename" value="<?= htmlspecialchars($imgFile) ?>">
                <button type="submit"
                  class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow hover:bg-red-600 transition">×</button>
              </form>
              <div class="text-center text-xs text-gray-400 mt-1 truncate"><?= htmlspecialchars($imgFile) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <div class="text-sm text-gray-400 mb-5 py-4 text-center border border-dashed border-gray-200 rounded-xl">
            Sin imágenes cargadas
          </div>
          <?php endif; ?>

          <form method="POST" action="/admin/panel?action=upload&id=<?= $indice ?>"
            enctype="multipart/form-data"
            class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-navy transition">
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
              class="hidden" id="upload-<?= $indice ?>"
              onchange="this.closest('form').submit()">
            <label for="upload-<?= $indice ?>" class="cursor-pointer">
              <div class="text-2xl mb-1">📎</div>
              <div class="text-sm font-semibold text-gray-600">Subir imagen</div>
              <div class="text-xs text-gray-400">JPG, PNG, WEBP · máx. 20 MB</div>
            </label>
          </form>
        </div>

      </div>
    </div>
  </details>
  <?php endforeach; ?>
  </div>

  <!-- Mensaje sin resultados -->
  <div id="no-results" class="hidden text-center py-16 text-gray-400">
    <div class="text-4xl mb-3">🔍</div>
    <p class="font-semibold">Sin resultados</p>
    <p class="text-sm mt-1">Intenta con otro término o limpia los filtros.</p>
  </div>

</main>

<script>
function toggleNewForm() {
  const form = document.getElementById('new-product-form');
  form.classList.toggle('open');
  if (form.classList.contains('open')) {
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    form.querySelector('input[name="nombre_web"]')?.focus();
  }
}

function applyFilters() {
  const search = document.getElementById('filter-search').value.toLowerCase().trim();
  const cat    = document.getElementById('filter-cat').value.toLowerCase();
  const stock  = document.getElementById('filter-stock').value;

  const rows   = document.querySelectorAll('.prod-row');
  let visible  = 0;

  rows.forEach(row => {
    const name  = row.dataset.name  || '';
    const brand = row.dataset.brand || '';
    const rCat  = row.dataset.cat   || '';
    const rStk  = row.dataset.stock || '';

    const matchSearch = !search || name.includes(search) || brand.includes(search);
    const matchCat    = !cat    || rCat === cat;
    const matchStock  = !stock  || rStk === stock;

    if (matchSearch && matchCat && matchStock) {
      row.classList.remove('hidden-filter');
      visible++;
    } else {
      row.classList.add('hidden-filter');
    }
  });

  const total = rows.length;
  const countEl = document.getElementById('filter-count');
  countEl.textContent = (search || cat || stock)
    ? visible + ' de ' + total + ' productos'
    : '';

  document.getElementById('no-results').classList.toggle('hidden', visible > 0);
}

function resetFilters() {
  document.getElementById('filter-search').value = '';
  document.getElementById('filter-cat').value    = '';
  document.getElementById('filter-stock').value  = '';
  applyFilters();
}
</script>

</body>
</html>
