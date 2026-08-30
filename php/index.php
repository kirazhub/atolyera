<?php
/** Ana sayfa (main) */
$PAGE_TITLE = 'İstanbul El Yapımı Kimono & Sabahlık — Lüks Sanat Kimono | Atölye RA';
$PAGE_DESC  = 'İstanbul’da elde işlenen, her deseni tek lüks kimono ve sabahlıklar. İpek saten, saten, viskon ve TENCEL üzerine giyilebilir sanat. Atölye RA — Eyüpsultan, İstanbul.';
require __DIR__ . '/partials/header.php';

$featured = array_slice(products_all('baski'), 0, 3);
$tilsim   = array_slice(products_all('seri'), 0, 3);
?>

<section class="page-hero">
  <p class="page-hero__kicker">Giyilebilir Sanat</p>
  <h1 class="page-hero__title">Aynısı bir daha doğmaz</h1>
  <p class="page-hero__intro"><strong>İstanbul’da</strong>, yağlı boya tablolardan doğan desenlerle elde işlenen <strong>lüks kimono ve sabahlıklar</strong>; ipek saten, saten ve viskon
  üzerine. Tılsım serisi ise <strong>TENCEL</strong> üzerine nakışla, <strong>30’da bir üretilen</strong>
  sınırlı bir edisyondur — her parça bir imza, her sırt bir tuval.</p>
  <p class="hero-fabrics">İpek Saten · Saten · Viskon · Viskon Şifon &nbsp;|&nbsp; TENCEL — sınırlı tılsım edisyonu (30’da 1)</p>
  <p style="margin-top:26px;">
    <a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyonu Keşfet</a>
  </p>
</section>

<div class="trust">
  <div><p class="t">Elde İşçilik</p><p class="d">Usta terzi, tek tek</p></div>
  <div><p class="t">3 İş Günü</p><p class="d">Tam paketleme ile kapında</p></div>
  <div><p class="t">Güvenli Ödeme</p><p class="d">Havale & kart</p></div>
  <div><p class="t">14 Gün İade</p><p class="d">Koşulsuz cayma hakkı</p></div>
</div>

<div class="grid-wrap">
  <div class="mini-head" style="margin-bottom:40px;">
    <p class="page-hero__kicker">Sanat Baskı Koleksiyonu</p>
    <h2 class="page-hero__title" style="font-size:clamp(26px,4vw,40px);">Öne Çıkanlar</h2>
  </div>
  <div class="grid">
    <?php foreach ($featured as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
  </div>

  <div class="mini-head" style="margin:70px 0 40px;">
    <p class="page-hero__kicker">Tılsım Serisi · Sınırlı</p>
    <h2 class="page-hero__title" style="font-size:clamp(26px,4vw,40px);">Tılsımını Taşıyanlar</h2>
  </div>
  <div class="grid">
    <?php foreach ($tilsim as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
  </div>

  <p style="text-align:center;margin-top:56px;">
    <a href="<?= url('/urunler') ?>" class="btn btn--ghost">Tüm Koleksiyon (<?= count(products_all()) ?> parça)</a>
  </p>
</div>

<section class="seo-note">
  <div class="seo-note__in">
    <h2>İstanbul’da El Yapımı Kimono ve Sabahlık</h2>
    <p>Atölye RA, <strong>İstanbul</strong> merkezli bir giyilebilir sanat atölyesidir. Yağlı boya tablolardan
    doğan desenlerimiz; ipek saten, saten ve viskon üzerine tek tek, elde işlenir. Her <strong>kimono</strong> ve
    <strong>sabahlık</strong> yalnızca bir kez üretilir — bu yüzden aldığınız parçanın dünyada bir eşi daha yoktur.</p>
    <p>Sınırlı <a href="<?= url('/kategori/seri') ?>">tılsım serimiz</a> TENCEL üzerine nakışla, 30’da bir üretilir.
    Size özel ölçü, isim veya şiir nakışı ve dilediğiniz tılsımla <a href="<?= url('/ozel-uretim') ?>">özel üretim</a>
    kimono da hazırlıyoruz. Tüm Türkiye’ye ücretsiz kargo; havale ile ödemede her gün indirim.</p>
    <p class="seo-note__tags">Lüks kimono · el yapımı sabahlık · ipek saten kimono · sanat kimono · İstanbul kimono · özel tasarım kimono</p>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
