<?php
/** Satış Noktaları / Butikler (stores) */
$PAGE_TITLE = 'Satış Noktaları — Atölye RA';
$PAGE_DESC  = 'Atölye RA parçalarını yerinde görüp satın alabileceğiniz butikler ve adresleri.';
require __DIR__ . '/partials/header.php';
$stores = config('stores', []);
?>
<section class="page-hero">
  <p class="page-hero__kicker">Yerinde Görün</p>
  <h1 class="page-hero__title">Satış Noktaları</h1>
  <p class="page-hero__intro">Atölye RA parçalarını <strong>Beymen mağazalarında</strong> ve seçkin
  butiklerde görüp deneyebilirsiniz. Aşağıdan size en yakın noktayı haritada bulun.</p>
</section>

<div class="stores">
  <?php if ($stores): foreach ($stores as $s): ?>
    <div class="store">
      <div>
        <h2 class="store__name"><?= e($s['name']) ?></h2>
        <p class="store__addr"><?= nl2br(e($s['address'])) ?></p>
        <?php if (!empty($s['note'])): ?><p class="store__note"><?= e($s['note']) ?></p><?php endif; ?>
      </div>
      <div style="align-self:center;">
        <?php if (!empty($s['maps'])): ?>
          <a href="<?= e($s['maps']) ?>" target="_blank" rel="noopener" class="btn btn--ghost btn--sm">Haritada Gör ↗</a>
        <?php else: ?>
          <a href="https://www.google.com/maps/search/<?= urlencode($s['name'].' '.$s['address']) ?>" target="_blank" rel="noopener" class="btn btn--ghost btn--sm">Haritada Ara ↗</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; else: ?>
    <p style="text-align:center;font-family:var(--serif-text);font-size:19px;color:var(--graphite-soft);">Satış noktaları yakında güncellenecek.</p>
  <?php endif; ?>

  <p style="text-align:center;margin-top:40px;font-family:var(--serif-text);color:var(--graphite-soft);">
    Ayrıca tüm koleksiyona <a href="<?= url('/urunler') ?>" style="color:var(--bordeaux);">buradan</a> online ulaşabilirsiniz.
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
