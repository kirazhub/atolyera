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

    <?php if ($order): ?>
      <p class="success__code">Sipariş No: <strong><?= e($order['order_code']) ?></strong></p>
      <p style="font-family:var(--serif-text);font-size:19px;line-height:1.8;color:var(--graphite-soft);">
        Değerli <?= e($order['customer_name']) ?>, seçtiğiniz parça için teşekkür ederiz.
        Sipariş onayı <strong><?= e($order['email']) ?></strong> adresine gönderildi.
        Ödeme için en kısa sürede sizinle iletişime geçeceğiz.
      </p>

      <?php if (config('payment.mode') === 'manual' && config('payment.bank_info')): ?>
        <div style="background:var(--ivory-2);padding:22px;margin:26px 0;text-align:left;">
          <p style="font-family:var(--sans);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--bordeaux);margin:0 0 8px;">Ödeme Bilgileri</p>
          <p style="font-family:var(--serif-text);font-size:17px;color:var(--graphite);margin:0;">
            <?= nl2br(e(config('payment.bank_info'))) ?><br>
            <span style="font-size:14px;color:var(--graphite-soft);">Açıklamaya sipariş numaranızı (<?= e($order['order_code']) ?>) yazınız.</span>
          </p>
        </div>
      <?php endif; ?>

      <div style="text-align:left;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:18px 0;margin:20px 0;">
        <?php
          $st = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $st->execute([$order['id']]);
          foreach ($st->fetchAll() as $it): ?>
          <div class="order-line"><span><?= e($it['name']) ?> × <?= (int)$it['qty'] ?></span><span><?= money($it['line_total']) ?></span></div>
        <?php endforeach; ?>
        <div class="order-total"><span>Toplam</span><span><?= money($order['total']) ?></span></div>
      </div>
    <?php else: ?>
      <p style="font-family:var(--serif-text);font-size:19px;color:var(--graphite-soft);">Siparişiniz alınmıştır.</p>
    <?php endif; ?>

    <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyona Dön</a></p>
    <p style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);margin-top:24px;">Aynısı bir daha doğmaz. — Atölye RA</p>
  </div>
</div>
<?php unset($_SESSION['last_order']); require __DIR__ . '/partials/footer.php'; ?>
