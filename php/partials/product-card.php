<?php
/** Ürün kartı (grid) — $p ürün satırını bekler */
$href = url('/urun/' . $p['slug']);
?>
<article class="card">
  <a href="<?= $href ?>" class="card__pic <?= !empty($p['is_sold']) ? 'is-sold' : '' ?>" aria-label="<?= e($p['name']) ?>">
    <picture>
      <source type="image/webp" srcset="<?= asset('images/' . $p['image_webp']) ?>">
      <img src="<?= asset('images/' . $p['image']) ?>" alt="<?= e($p['name']) ?> — Atölye RA"
           loading="lazy" decoding="async" width="<?= (int)$p['width'] ?>" height="<?= (int)$p['height'] ?>">
    </picture>
    <?php if (!empty($p['is_sold'])): ?><span class="card__badge card__badge--sold">Sahibini Buldu</span>
    <?php elseif (!empty($p['badge'])): ?><span class="card__badge"><?= e($p['badge']) ?></span><?php endif; ?>
  </a>
  <div class="card__body">
    <p class="card__no"><?= e($p['no_label']) ?></p>
    <h3 class="card__name"><a href="<?= $href ?>"><?= e($p['name']) ?></a></h3>
    <p class="card__price"><?= money($p['price']) ?></p>
    <div class="card__actions">
      <a href="<?= $href ?>" class="btn btn--ghost">İncele</a>
      <?php if (!empty($p['is_sold'])): ?>
        <span class="btn btn--sold" aria-disabled="true">Sahibini Buldu</span>
      <?php else: ?>
        <form method="post" action="<?= url('/sepet-islem') ?>" class="card__add">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <button type="submit" class="btn btn--solid">Sepete Ekle</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</article>
