<?php
/**
 * Ortak ÜST parça: <head> + navigasyon.
 * Kullanım: sayfanın başında değişkenleri ayarla, sonra include et.
 *   $PAGE_TITLE, $PAGE_DESC, $CANON (opsiyonel), $OG_IMAGE (opsiyonel)
 */
require_once __DIR__ . '/../includes/bootstrap.php';

$PAGE_TITLE = $PAGE_TITLE ?? 'Kimono & Sabahlık | Lüks El Yapımı — Atölye RA';
$PAGE_DESC  = $PAGE_DESC  ?? 'Giyilebilir sanat: TENCEL ve saten kumaştan, elde işlenmiş, her deseni tek lüks kimono ve sabahlık. Atölye RA.';
$CANON      = $CANON      ?? site_url(ltrim($_SERVER['REQUEST_URI'] ?? '/', '/'));
$OG_IMAGE   = $OG_IMAGE   ?? site_url('images/1.jpg');
$cartN      = cart_count();
?><!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($PAGE_TITLE) ?></title>
  <meta name="description" content="<?= e($PAGE_DESC) ?>">
  <meta name="robots" content="index, follow, max-image-preview:large">
  <link rel="canonical" href="<?= e($CANON) ?>">
  <meta name="theme-color" content="#1A1A1A">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Atölye RA">
  <meta property="og:locale" content="tr_TR">
  <meta property="og:title" content="<?= e($PAGE_TITLE) ?>">
  <meta property="og:description" content="<?= e($PAGE_DESC) ?>">
  <meta property="og:url" content="<?= e($CANON) ?>">
  <meta property="og:image" content="<?= e($OG_IMAGE) ?>">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" type="image/png" href="<?= asset('images/logo-dark.png') ?>">
  <link rel="apple-touch-icon" href="<?= asset('images/logo-dark.png') ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,400&family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/shop.css') ?>">

  <meta name="google-site-verification" content="mXsoROE7EtKlPsS2gYWDoqI_tcNQ_hRE8vdbAi6OYFA">
  <link rel="sitemap" type="application/xml" href="<?= asset('sitemap.xml') ?>">
  <script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    [ '@type' => 'Organization', '@id' => site_url().'#org', 'name' => 'Atölye RA',
      'url' => site_url(), 'logo' => site_url('images/logo-dark.png'),
      'email' => config('company.email'), 'telephone' => config('company.phone'),
      'description' => 'İstanbul’da elde işlenen lüks kimono ve sabahlık: ipek saten, saten, viskon ve TENCEL üzerine giyilebilir sanat.',
      'address' => ['@type'=>'PostalAddress','streetAddress'=>'Mithatpaşa Mah. Bahçeköy Cad. No:55','addressLocality'=>'Eyüpsultan','addressRegion'=>'İstanbul','addressCountry'=>'TR'],
      'areaServed' => ['İstanbul','Türkiye'],
      'contactPoint' => ['@type'=>'ContactPoint','email'=>config('company.email'),'telephone'=>config('company.phone'),'contactType'=>'customer support','areaServed'=>'TR','availableLanguage'=>['Turkish']] ],
    [ '@type' => 'WebSite', '@id' => site_url().'#site', 'url' => site_url(), 'name' => 'Atölye RA',
      'inLanguage' => 'tr-TR', 'publisher' => ['@id'=>site_url().'#org'],
      'potentialAction' => ['@type'=>'SearchAction','target'=>['@type'=>'EntryPoint','urlTemplate'=>site_url('urunler?q={search_term_string}')],'query-input'=>'required name=search_term_string'] ],
    [ '@type' => 'ClothingStore', '@id' => site_url().'#store',
      'name' => 'Atölye RA — El Yapımı Kimono, İstanbul',
      'image' => site_url('images/logo-dark.png'), 'url' => site_url(),
      'telephone' => config('company.phone'), 'email' => config('company.email'),
      'priceRange' => '$$$', 'currenciesAccepted' => 'TRY, USD, EUR', 'paymentAccepted' => 'Havale/EFT, Kredi Kartı',
      'description' => 'İstanbul’da el yapımı lüks kimono ve sabahlık. Her deseni tek, elde işlenmiş giyilebilir sanat; ipek saten, saten, viskon ve TENCEL.',
      'address' => ['@type'=>'PostalAddress','streetAddress'=>'Mithatpaşa Mah. Bahçeköy Cad. No:55','addressLocality'=>'Eyüpsultan','addressRegion'=>'İstanbul','addressCountry'=>'TR'],
      'areaServed' => ['İstanbul','Türkiye'], 'parentOrganization' => ['@id'=>site_url().'#org'] ],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
  </script>
</head>
<body>
  <a href="#top" id="top"></a>

  <header class="nav" id="nav">
    <button class="nav__menu" aria-label="Menü" id="menuBtn"><span></span><span></span></button>

    <a href="<?= url('/') ?>" class="nav__logo"><img src="<?= asset('images/logo-white.png') ?>" alt="Atölye RA" class="nav__logo-img"></a>

    <nav class="nav__links" id="navLinks">
      <a href="<?= url('/urunler') ?>">Koleksiyon</a>
      <a href="<?= url('/kategori/tilsim') ?>">Tılsım</a>
      <a href="<?= url('/kategori/seri') ?>">Tılsım Serisi</a>
      <a href="<?= url('/ozel-uretim') ?>">Özel Üretim</a>
      <a href="<?= url('/satis-noktalari') ?>">Satış Noktaları</a>
      <a href="mailto:<?= e(config('company.email')) ?>">İletişim</a>
      <a href="<?= url('/urunler') ?>" class="nav__search" aria-label="Ara">
        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
      </a>
      <?php $curNow = function_exists('current_currency') ? current_currency() : 'TRY'; ?>
      <span class="nav__cur">
        <?php foreach (config('currencies', []) as $code => $meta):
          if ($code !== 'TRY' && !fx_available()) continue; ?>
          <a href="?cur=<?= $code ?>" class="<?= $curNow === $code ? 'is-active' : '' ?>" title="<?= e($meta['label']) ?>"><?= e($meta['symbol']) ?></a>
        <?php endforeach; ?>
      </span>
      <a href="<?= url('/sepet') ?>" class="nav__cart" aria-label="Sepet">Sepet<?php if ($cartN): ?> <span class="nav__cart-count"><?= $cartN ?></span><?php endif; ?></a>
    </nav>
  </header>

  <?php foreach (flash_all() as $f): ?>
    <div class="flash flash--<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
  <?php endforeach; ?>
