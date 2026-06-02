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
    $max = 0;
    foreach ($files as $f) {
        if (preg_match('/^' . $indice . '[_.\-](\d+)/i', basename($f), $m)) {
            $max = max($max, (int)$m[1]);
        }
    }
    return $max + 1;
}

// ── Actions ───────────────────────────────────────────────────────────────────

$action = $_GET['action'] ?? '';

// Logout
if ($action === 'logout') {
    session_destroy();
    header('Location: /admin/panel');
    exit;
}

// Auth check — si no autenticado, manejar login o mostrar form
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
    // Mostrar pantalla de login
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

$savedId    = isset($_GET['saved']) ? (int)$_GET['saved'] : 0;
$writeError = !empty($_GET['write_error']);
$errorInfo  = $writeError ? [
    'file'     => $_GET['file']     ?? DATA_PATH,
    'writable' => $_GET['writable'] ?? '?',
    'owner'    => $_GET['owner']    ?? '?',
    'process'  => $_GET['process']  ?? '?',
] : [];
$flashMsg   = '';

// Editable fields
$editableFields = [
    'nombre_web'         => ['label' => 'Nombre web',        'type' => 'text'],
    'marca'              => ['label' => 'Marca',              'type' => 'text'],
    'modelo'             => ['label' => 'Modelo',             'type' => 'text'],
    'categoria'          => ['label' => 'Categoría',          'type' => 'text'],
    'capacidad'          => ['label' => 'Capacidad',          'type' => 'text'],
    'precio_venta_cop'   => ['label' => 'Precio venta (COP)', 'type' => 'number'],
    'precio_mercado_cop' => ['label' => 'Precio mercado (COP)','type' => 'number'],
    'precio_usd'         => ['label' => 'Precio USD',         'type' => 'number'],
    'cantidad'           => ['label' => 'Stock',              'type' => 'number'],
    'descripcion'        => ['label' => 'Descripción',        'type' => 'textarea'],
];

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
            // Sync precio_web con precio_venta_cop
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
        $path    = DATA_PATH;
        $writable = is_writable($path) ? 'sí' : 'NO';
        $owner   = function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] ?? '?' : '?';
        $process = function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] ?? '?' : '?';
        header('Location: /admin/panel?write_error=1&file=' . urlencode($path)
            . '&writable=' . urlencode($writable)
            . '&owner=' . urlencode($owner)
            . '&process=' . urlencode($process)
            . '#prod-' . $id);
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

$data     = readInventory();
$products = [];
foreach (($data['orden_productos'] ?? array_keys($data['productos'] ?? [])) as $key) {
    if (!isset($data['productos'][$key])) continue;
    $p            = $data['productos'][$key];
    $p['_images'] = getProductImages((int)$p['indice']);
    $products[]   = $p;
}
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
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Header -->
<header class="bg-navy text-white px-6 py-4 flex items-center justify-between sticky top-0 z-50 shadow-lg">
  <div class="font-black text-xl tracking-widest" style="font-family:'Bebas Neue',sans-serif">
    FROMUSA <span class="text-xs font-normal tracking-normal opacity-60 ml-2">ADMIN</span>
  </div>
  <a href="/admin/panel?action=logout"
    class="text-xs bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">
    Cerrar sesión
  </a>
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
<main class="max-w-5xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-navy">Productos <span class="text-base font-normal text-gray-400">(<?= count($products) ?> en catálogo)</span></h1>
    <a href="/" target="_blank" class="text-sm text-usablue hover:underline">Ver sitio →</a>
  </div>

  <div class="space-y-3">
  <?php foreach ($products as $p):
    $indice  = (int)$p['indice'];
    $isOpen  = $savedId === $indice;
    $images  = $p['_images'];
    $precio  = isset($p['precio_venta_cop']) ? '$' . number_format((int)$p['precio_venta_cop'], 0, ',', '.') : '—';
    $stock   = $p['cantidad'] ?? 0;
  ?>
  <details id="prod-<?= $indice ?>" <?= $isOpen ? 'open' : '' ?>
    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Fila resumen -->
    <summary class="flex items-center gap-4 px-5 py-4 cursor-pointer hover:bg-gray-50 transition select-none">
      <!-- Miniatura -->
      <div class="w-12 h-12 flex-shrink-0 rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
        <?php if (!empty($images)): ?>
          <img src="<?= IMG_WEB . htmlspecialchars($images[0]) ?>" class="w-full h-full object-contain" loading="lazy" alt="">
        <?php else: ?>
          <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
        <?php endif; ?>
      </div>
      <!-- Info -->
      <div class="flex-1 min-w-0">
        <div class="font-semibold text-sm truncate"><?= htmlspecialchars($p['nombre_web'] ?? '') ?></div>
        <div class="text-xs text-gray-400"><?= htmlspecialchars($p['marca'] ?? '') ?> · <?= htmlspecialchars($p['categoria'] ?? '') ?></div>
      </div>
      <!-- Precio + stock -->
      <div class="text-right flex-shrink-0 hidden sm:block">
        <div class="font-bold text-usared text-sm"><?= $precio ?></div>
        <div class="text-xs <?= $stock > 0 ? 'text-green-600' : 'text-red-500' ?>">
          <?= $stock > 0 ? $stock . ' en stock' : 'Sin stock' ?>
        </div>
      </div>
      <!-- # -->
      <div class="text-xs text-gray-300 hidden md:block flex-shrink-0">#<?= $indice ?></div>
      <!-- Chevron -->
      <svg class="chevron w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </summary>

    <!-- Panel expandido -->
    <div class="border-t border-gray-100 px-5 py-6">
      <div class="grid md:grid-cols-2 gap-8">

        <!-- Columna izquierda: formulario de edición -->
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

        <!-- Columna derecha: imágenes -->
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
                  class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow hover:bg-red-600 transition">
                  ×
                </button>
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

          <!-- Upload -->
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

</main>

</body>
</html>
