<?php
/**
 * Yerel geliştirme yönlendiricisi (yalnızca `php -S` için).
 * Canlıda (Apache/cPanel) .htaccess kullanılır; bu dosya orada devreye girmez.
 * Çalıştır:  php -S localhost:8080 -t php php/router.php
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$root = __DIR__;

// Gerçek dosya varsa (css, js, images, setup.php...) doğrudan servis et
$file = realpath($root . $uri);
if ($file && is_file($file) && strpos($file, $root) === 0) return false;

// Temiz URL eşlemeleri
$routes = [
    '#^/?$#'                       => '/index.php',
    '#^/urunler/?$#'               => '/products.php',
    '#^/kategori/([a-z0-9-]+)/?$#' => '/category.php?slug=$1',
    '#^/urun/([a-z0-9-]+)/?$#'     => '/product.php?slug=$1',
    '#^/sepet/?$#'                 => '/basket.php',
    '#^/sepet-islem/?$#'          => '/cart-action.php',
    '#^/odeme/?$#'                => '/checkout.php',
    '#^/satis-noktalari/?$#'      => '/stores.php',
    '#^/iletisim/?$#'             => '/iletisim.php',
    '#^/hakkimizda/?$#'           => '/hakkimizda.php',
    '#^/sss/?$#'                  => '/sss.php',
    '#^/sitemap\.xml$#'           => '/sitemap.php',
    '#^/siparis-tamam/?$#'       => '/order-success.php',
    '#^/yasal/([a-z0-9-]+)/?$#'   => '/legal/$1.php',
    '#^/admin/?$#'                => '/admin/index.php',
];
foreach ($routes as $rx => $target) {
    if (preg_match($rx, $uri, $m)) {
        $t = preg_replace_callback('/\$(\d+)/', fn($x) => $m[(int)$x[1]] ?? '', $target);
        [$path, $qs] = array_pad(explode('?', $t, 2), 2, '');
        if ($qs) { parse_str($qs, $q); $_GET = array_merge($_GET, $q); }
        $abs = $root . $path;
        if (is_file($abs)) { $_SERVER['SCRIPT_NAME'] = $path; require $abs; return true; }
    }
}
// admin/legal alt yolları (dosya bazlı)
$abs = $root . $uri;
if (is_file($abs)) { require $abs; return true; }

http_response_code(404);
require $root . '/404.php';
return true;
