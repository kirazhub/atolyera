<?php
/** Tüm ürünler (products) — arama + filtre + sıralama */
require_once __DIR__ . '/includes/bootstrap.php';
$q        = trim($_GET['q'] ?? '');
$material = trim($_GET['material'] ?? '');
$sort     = $_GET['sort'] ?? '';

$PAGE_TITLE = ($q !== '' ? '“' . $q . '” — Arama · ' : '') . 'Kimono Koleksiyonu — El Yapımı Sanat Kimonolar | Atölye RA İstanbul';
$PAGE_DESC  = 'Atölye RA kimono koleksiyonu: İstanbul’da elde işlenmiş sanat baskı kimonolar, tılsım koleksiyonu ve sınırlı tılsım serisi. İpek saten, saten, viskon ve TENCEL.';
require __DIR__ . '/partials/header.php';

$cats  = categories();
$items = products_list(['q' => $q, 'material' => $material, 'sort' => $sort]);
$materials = ['İpek Saten', 'Saten', 'Viskon', 'TENCEL'];
function qs(array $over) { $p = array_merge($_GET, $over); $p = array_filter($p, fn($v)=>$v!==''&&$v!==null); return $p ? '?' . http_build_query($p) : ''; }
?>
<section class="page-hero">
  <p class="page-hero__kicker">Giyilebilir Sanat</p>
  <h1 class="page-hero__title"><?= $q !== '' ? 'Arama Sonuçları' : 'Koleksiyon' ?></h1>
  <?php if ($q !== ''): ?>
    <p class="page-hero__intro">“<?= e($q) ?>” için <?= count($items) ?> sonuç.</p>
  <?php else: ?>
    <p class="page-hero__intro">Her biri elde işlenmiş. Bir parçayı seçmek, bir tabloyu sırtınıza almaktır.</p>
  <?php endif; ?>
</section>

<form class="search-bar" method="get" action="<?= url('/urunler') ?>">
  <input type="search" name="q" value="<?= e($q) ?>" placeholder="Ara: isim, kumaş, tılsım…" aria-label="Ara">
  <?php if ($material): ?><input type="hidden" name="material" value="<?= e($material) ?>"><?php endif; ?>
  <button type="submit" aria-label="Ara">
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
  </button>
</form>

<nav class="cat-tabs">
  <a href="<?= url('/urunler') . (($material||$sort)?qs(['q'=>$q]):'') ?>" class="<?= $q===''?'':'' ?>">Tümü</a>
  <?php foreach ($cats as $c): ?>
    <a href="<?= url('/kategori/' . $c['slug']) ?>"><?= e($c['name']) ?></a>
  <?php endforeach; ?>
</nav>

<div class="filter-bar">
  <div class="filter-mats">
    <span>Kumaş:</span>
    <a href="<?= url('/urunler') . qs(['material'=>'']) ?>" class="<?= $material===''?'is-active':'' ?>">Tümü</a>
    <?php foreach ($materials as $m): ?>
      <a href="<?= url('/urunler') . qs(['material'=>$m]) ?>" class="<?= $material===$m?'is-active':'' ?>"><?= e($m) ?></a>
    <?php endforeach; ?>
  </div>
  <form method="get" action="<?= url('/urunler') ?>" class="filter-sort">
    <?php if ($q): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
    <?php if ($material): ?><input type="hidden" name="material" value="<?= e($material) ?>"><?php endif; ?>
    <label>Sırala:</label>
    <select name="sort" onchange="this.form.submit()">
      <option value="">Öne çıkanlar</option>
      <option value="price_asc"  <?= $sort==='price_asc'?'selected':'' ?>>Fiyat ↑</option>
      <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Fiyat ↓</option>
      <option value="new"        <?= $sort==='new'?'selected':'' ?>>Yeni</option>
    </select>
  </form>
</div>

<div class="grid-wrap">
  <?php if ($items): ?>
    <div class="grid">
      <?php foreach ($items as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;font-family:var(--serif-text);font-size:20px;color:var(--graphite-soft);padding:40px 0;">
      Sonuç bulunamadı. <a href="<?= url('/urunler') ?>" style="color:var(--bordeaux);">Tüm koleksiyona dön</a>.
    </p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
