<?php
/** Sepet (basket) */
$PAGE_TITLE = 'Sepet — Atölye RA';
$PAGE_DESC  = 'Sepetiniz.';
require __DIR__ . '/partials/header.php';

$items = cart_items();
?>
<div class="shell">
  <div class="mini-head" style="margin-bottom:34px;">
    <p class="page-hero__kicker">Seçtikleriniz</p>
    <h1 class="page-hero__title" style="font-size:clamp(30px,5vw,46px);">Sepet</h1>
  </div>

  <?php if (!$items): ?>
    <div class="cart-empty">
      <p>Sepetiniz henüz boş.</p>
      <p style="margin-top:20px;"><a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyona Dön</a></p>
    </div>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr><th>Parça</th><th>Adet</th><th class="cart-td--right">Tutar</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
        <tr>
          <td>
            <div class="cart-row">
              <a href="<?= url('/urun/' . $it['slug']) ?>">
                <img src="<?= asset('images/' . $it['image']) ?>" alt="<?= e($it['name']) ?>" width="84" height="112">
              </a>
              <div>
                <a href="<?= url('/urun/' . $it['slug']) ?>" class="cart-row__name"><?= e($it['name']) ?></a>
                <div class="cart-row__meta"><?= e($it['no_label']) ?> · <?= e($it['size']) ?> · <?= money($it['price']) ?></div>
              </div>
            </div>
          </td>
          <td>
            <form method="post" action="<?= url('/sepet-islem') ?>" class="qty" style="display:inline-flex;">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
              <button type="submit" name="qty" value="<?= max(1,$it['qty']-1) ?>" aria-label="Azalt">−</button>
              <input type="number" name="qty" value="<?= (int)$it['qty'] ?>" min="1" max="9" onchange="this.form.submit()">
              <button type="submit" name="qty" value="<?= $it['qty']+1 ?>" aria-label="Arttır">+</button>
            </form>
          </td>
          <td class="cart-td--right"><?= money($it['line_total']) ?></td>
          <td class="cart-td--right">
            <form method="post" action="<?= url('/sepet-islem') ?>">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
              <button type="submit" class="cart-remove">Çıkar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="cart-summary">
      <div class="cart-summary__line"><span>Ara Toplam</span><span><?= money(cart_subtotal()) ?></span></div>
      <div class="cart-summary__line"><span>Kargo</span><span><?= config('shipping_fee') ? money(config('shipping_fee')) : 'Ücretsiz' ?></span></div>
      <div class="cart-summary__total"><span>Toplam</span><span><?= money(cart_total()) ?></span></div>
      <a href="<?= url('/odeme') ?>" class="btn btn--solid btn--wide" style="margin-top:18px;">Siparişi Tamamla</a>
      <p style="text-align:center;margin-top:14px;"><a href="<?= url('/urunler') ?>" style="font-family:var(--sans);font-size:12px;letter-spacing:.1em;color:var(--graphite-soft);">← Alışverişe devam et</a></p>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
