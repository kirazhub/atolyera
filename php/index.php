<?php
/** Ana sayfa (main) */
$PAGE_TITLE = 'Kimono & Sabahlık | Lüks El Yapımı — Atölye RA';
$PAGE_DESC  = 'Giyilebilir sanat: TENCEL ve saten kumaştan, elde işlenmiş, her deseni tek lüks kimono ve sabahlık. Atölye RA.';
require __DIR__ . '/partials/header.php';

$featured = array_slice(products_all('baski'), 0, 3);
$tilsim   = array_slice(products_all('seri'), 0, 3);
?>

<section class="page-hero">
  <p class="page-hero__kicker">Giyilebilir Sanat</p>
  <h1 class="page-hero__title">Aynısı bir daha doğmaz</h1>
  <p class="page-hero__intro">Yağlı boya tablolardan doğan desenler, elde işlenmiş kumaşlar.
  İpek değil TENCEL; her parça bir imza, her sırt bir tuval.</p>
  <p style="margin-top:30px;">
    <a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyonu Keşfet</a>
  </p>
</section>

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

<?php require __DIR__ . '/partials/footer.php'; ?>
