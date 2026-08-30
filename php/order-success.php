<?php
/** Sipariş sonucu (order-success) */
require_once __DIR__ . '/includes/bootstrap.php';

$code = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['code'] ?? ($_SESSION['last_order'] ?? ''));
$order = null;
if ($code) {
    $st = db()->prepare('SELECT * FROM orders WHERE order_code = ?');
    $st->execute([$code]); $order = $st->fetch();
}

$PAGE_TITLE = 'Siparişiniz Alındı — Atölye RA';
$PAGE_DESC  = 'Teşekkürler.';
require __DIR__ . '/partials/header.php';
?>
<div class="shell shell--narrow">
  <div class="success">
    <p class="page-hero__kicker">Teşekkürler</p>
    <h1 class="page-hero__title" style="font-size:clamp(30px,5vw,48px);">Siparişiniz Alındı</h1>

    <?php if ($order):
      $isCard = ($order['payment_method'] ?? 'bank') === 'card';
      $paid   = ($order['payment_status'] ?? '') === 'odendi';
      $oc     = $order['display_currency'] ?? 'TRY';
      $curLabel = config("currencies.$oc.label", 'TL');
    ?>
      <p class="success__code">Sipariş No: <strong><?= e($order['order_code']) ?></strong></p>
      <p style="font-family:var(--serif-text);font-size:19px;line-height:1.8;color:var(--graphite-soft);">
        Değerli <?= e($order['customer_name']) ?>, seçtiğiniz parça için teşekkür ederiz.
        <?php if ($isCard && $paid): ?>Ödemeniz güvenle alındı.<?php endif; ?>
        Sipariş onayı <strong><?= e($order['email']) ?></strong> adresine gönderildi.
      </p>
      <p style="font-family:var(--sans);font-size:13px;letter-spacing:.04em;color:var(--bordeaux);margin:8px 0 0;">
        ✦ Tam (özel) paketleme ile <strong>3 iş günü</strong> içinde kapınızda.
      </p>

      <?php if (!$isCard): // Havale / EFT — TL hesabına ?>
        <div style="background:var(--ivory-2);padding:22px;margin:26px 0;text-align:left;">
          <p style="font-family:var(--sans);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--bordeaux);margin:0 0 8px;">Havale / EFT ile Ödeme</p>
          <p style="font-family:var(--serif-text);font-size:17px;color:var(--graphite);margin:0;line-height:1.9;">
            Hesap sahibi: <strong><?= e(config('bank_account_name')) ?></strong><br>
            IBAN: <strong><?= e(currency_iban('TRY')) ?></strong><br>
            Tutar: <strong><?= money($order['total'], 'TRY') ?></strong>
            <?php if ($oc !== 'TRY'): ?><span style="color:var(--graphite-soft);font-size:14px;">(sitede <?= money($order['total'], $oc) ?> olarak gördünüz; havale TL olarak yapılır)</span><?php endif; ?><br>
            <span style="font-size:14px;color:var(--graphite-soft);">Açıklamaya sipariş numaranızı (<?= e($order['order_code']) ?>) yazınız. Ödemeniz onaylanınca kargolanır.</span>
          </p>
        </div>
      <?php endif; ?>

      <div style="text-align:left;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:18px 0;margin:20px 0;">
        <?php
          $st = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $st->execute([$order['id']]);
          foreach ($st->fetchAll() as $it): ?>
          <div class="order-line"><span><?= e($it['name']) ?> × <?= (int)$it['qty'] ?></span><span><?= money($it['line_total'], $oc) ?></span></div>
        <?php endforeach; ?>
        <div class="order-total"><span>Toplam</span><span><?= money($order['total'], $oc) ?></span></div>
      </div>
    <?php else: ?>
      <p style="font-family:var(--serif-text);font-size:19px;color:var(--graphite-soft);">Siparişiniz alınmıştır.</p>
    <?php endif; ?>

    <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyona Dön</a></p>
    <p style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);margin-top:24px;">Aynısı bir daha doğmaz. — Atölye RA</p>
  </div>
</div>
<?php unset($_SESSION['last_order']); require __DIR__ . '/partials/footer.php'; ?>
