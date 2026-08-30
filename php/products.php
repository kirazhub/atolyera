<?php
/** Tüm ürünler (products) */
$PAGE_TITLE = 'Koleksiyon — Tüm Kimono & Sabahlıklar | Atölye RA';
$PAGE_DESC  = 'Atölye RA koleksiyonu: sanat baskı kimonolar, tılsım koleksiyonu ve sınırlı tılsım serisi. Her deseni tek.';
require __DIR__ . '/partials/header.php';

$cats  = categories();
$items = products_all();
?>
<section class="page-hero">
  <p class="page-hero__kicker">Giyilebilir Sanat</p>
  <h1 class="page-hero__title">Koleksiyon</h1>
  <p class="page-hero__intro">Her biri elde işlenmiş, tek. Bir parçayı seçmek, bir tabloyu sırtınıza almaktır.</p>
</section>

<nav class="cat-tabs">
  <a href="<?= url('/urunler') ?>" class="is-active">Tümü</a>
  <?php foreach ($cats as $c): ?>
    <a href="<?= url('/kategori/' . $c['slug']) ?>"><?= e($c['name']) ?></a>
  <?php endforeach; ?>
</nav>

<div class="grid-wrap">
  <div class="grid">
    <?php foreach ($items as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
