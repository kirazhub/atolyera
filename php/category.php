<?php
/** Kategori (category) */
require_once __DIR__ . '/includes/bootstrap.php';
$slug = preg_replace('/[^a-z0-9-]/', '', $_GET['slug'] ?? '');
$cat  = $slug ? category_by_slug($slug) : null;
if (!$cat) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$PAGE_TITLE = e($cat['name']) . ' — Atölye RA';
$PAGE_DESC  = $cat['intro'] ?: ('Atölye RA ' . $cat['name']);
require __DIR__ . '/partials/header.php';

$cats  = categories();
$items = products_all($cat['slug']);
?>
<section class="page-hero">
  <p class="page-hero__kicker">Koleksiyon</p>
  <h1 class="page-hero__title"><?= e($cat['name']) ?></h1>
  <?php if ($cat['intro']): ?><p class="page-hero__intro"><?= e($cat['intro']) ?></p><?php endif; ?>
</section>

<nav class="cat-tabs">
  <a href="<?= url('/urunler') ?>">Tümü</a>
  <?php foreach ($cats as $c): ?>
    <a href="<?= url('/kategori/' . $c['slug']) ?>" class="<?= $c['slug'] === $cat['slug'] ? 'is-active' : '' ?>"><?= e($c['name']) ?></a>
  <?php endforeach; ?>
</nav>

<div class="grid-wrap">
  <?php if ($items): ?>
    <div class="grid">
      <?php foreach ($items as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;font-family:var(--serif-text);font-size:20px;color:var(--graphite-soft);">Bu kategoride henüz parça yok.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
