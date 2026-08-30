<?php
/** Dinamik sitemap.xml — ürünler + sayfalar (SEO) */
require_once __DIR__ . '/includes/bootstrap.php';
header('Content-Type: application/xml; charset=utf-8');

$base = rtrim(config('site_url', 'https://atolyera.com'), '/');
$today = date('Y-m-d');

$urls = [];
$add = function ($loc, $prio = '0.6', $img = null, $title = null) use (&$urls) {
    $urls[] = ['loc' => $loc, 'prio' => $prio, 'img' => $img, 'title' => $title];
};

// Statik sayfalar
$add($base . '/', '1.0');
$add($base . '/urunler', '0.9');
$add($base . '/satis-noktalari', '0.6');
$add($base . '/iletisim', '0.5');
$add($base . '/hakkimizda', '0.6');
$add($base . '/sss', '0.5');
foreach (['mesafeli-satis','on-bilgilendirme','iade-cayma','teslimat','gizlilik','kvkk','cerez'] as $p)
    $add($base . '/yasal/' . $p, '0.3');
// Kategoriler
foreach (categories() as $c) $add($base . '/kategori/' . $c['slug'], '0.7');
// Ürünler (görselli)
foreach (products_all() as $p)
    $add($base . '/urun/' . $p['slug'], '0.8', $base . '/images/' . $p['image'], $p['name']);
// Özel Üretim
require_once __DIR__ . '/includes/custom-designs.php';
$add($base . '/ozel-uretim', '0.8');
foreach (custom_designs() as $d)
    $add($base . '/ozel-uretim?d=' . $d['slug'], '0.7', $base . '/' . $d['img'], $d['name']);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
foreach ($urls as $u) {
    echo '  <url><loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>';
    echo '<lastmod>' . $today . '</lastmod>';
    echo '<priority>' . $u['prio'] . '</priority>';
    if (!empty($u['img'])) {
        echo '<image:image><image:loc>' . htmlspecialchars($u['img'], ENT_XML1) . '</image:loc>';
        echo '<image:title>' . htmlspecialchars($u['title'], ENT_XML1) . '</image:title></image:image>';
    }
    echo "</url>\n";
}
echo '</urlset>';
