<?php
/**
 * Genera la imagen Open Graph 1200×630 para redes sociales.
 * Requiere la extensión GD con soporte freetype (instalada en Dockerfile).
 */
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');

if (!function_exists('imagecreatetruecolor')) {
    http_response_code(503);
    exit;
}

$w = 1200;
$h = 630;
$img = imagecreatetruecolor($w, $h);
imagesavealpha($img, true);

// ── Colores ──────────────────────────────────────
$cNavy  = imagecolorallocate($img, 10,  22,  40);
$cRed   = imagecolorallocate($img, 232, 37,  58);
$cWhite = imagecolorallocate($img, 255, 255, 255);
$cBlue  = imagecolorallocate($img, 60,  59,  110);
$cGray  = imagecolorallocate($img, 155, 165, 180);
$cDark  = imagecolorallocate($img, 30,  45,  70);

// ── Fondo navy ────────────────────────────────────
imagefill($img, 0, 0, $cNavy);

// ── Bandera USA decorativa (franja derecha ~38%) ──
$flagX    = 752;
$nStripes = 13;
$sH       = $h / $nStripes;

for ($i = 0; $i < $nStripes; $i++) {
    $col = ($i % 2 === 0) ? $cRed : $cWhite;
    imagefilledrectangle($img, $flagX, (int)($i * $sH), $w, (int)(($i + 1) * $sH), $col);
}

// Cantón azul (cubre 7 franjas en ~40% del ancho de la bandera)
$cantonW = (int)(($w - $flagX) * 0.42);
$cantonH = (int)(7 * $sH);
imagefilledrectangle($img, $flagX, 0, $flagX + $cantonW, $cantonH, $cBlue);

// Estrellas en el cantón (cuadrícula 3×3)
for ($row = 0; $row < 3; $row++) {
    for ($col = 0; $col < 3; $col++) {
        $sx = (int)($flagX + 22 + $col * ($cantonW - 30) / 2);
        $sy = (int)(22 + $row * ($cantonH - 30) / 2);
        imagefilledellipse($img, $sx, $sy, 14, 14, $cWhite);
    }
}

// Desvanecimiento izquierdo de la bandera (100px gradiente hacia navy)
for ($x = $flagX; $x < $flagX + 100; $x++) {
    $a = (int)(127 * (1 - ($x - $flagX) / 100.0));
    $fade = imagecolorallocatealpha($img, 10, 22, 40, $a);
    imageline($img, $x, 0, $x, $h, $fade);
}

// ── Barra roja superior (acento) ─────────────────
imagefilledrectangle($img, 0, 0, $flagX, 6, $cRed);

// ── Tipografía ────────────────────────────────────
$fontBold = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
$fontReg  = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
$hasTTF   = file_exists($fontBold) && file_exists($fontReg);

if ($hasTTF) {
    // "from" — pequeño, gris
    imagettftext($img, 30, 0, 72, 240, $cGray, $fontReg, 'from');
    // "USA" — grande, rojo
    imagettftext($img, 148, 0, 58, 420, $cRed, $fontBold, 'USA');
    // ".com.co" — mediano, blanco
    imagettftext($img, 30, 0, 72, 470, $cWhite, $fontReg, '.com.co');
    // Línea separadora
    imagefilledrectangle($img, 72, 490, 480, 493, $cRed);
    // Subtítulo
    imagettftext($img, 21, 0, 72, 530, $cGray, $fontReg, 'Tecnología importada directo de Estados Unidos');
    // Tags de categorías
    imagettftext($img, 17, 0, 72, 575, $cDark, $fontReg, 'Celulares  ·  Tablets  ·  Audífonos  ·  Consolas  ·  Apple');
} else {
    // Fallback fuentes de mapa de bits
    imagestring($img, 5, 72, 220, 'from',                                 $cGray);
    imagestring($img, 5, 72, 280, 'USA',                                  $cRed);
    imagestring($img, 5, 72, 340, '.com.co',                              $cWhite);
    imagestring($img, 4, 72, 420, 'Tecnologia importada directo de USA',  $cGray);
}

// ── Barra roja inferior ───────────────────────────
imagefilledrectangle($img, 0, $h - 8, $w, $h, $cRed);

imagepng($img);
imagedestroy($img);
