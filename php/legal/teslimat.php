<?php
$PAGE_TITLE = 'Teslimat ve Kargo — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>Teslimat ve Kargo</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <h2>Hazırlık ve Gönderim</h2>
  <p>Her parça özenle, <strong>tam (özel) paketleme</strong> ile hazırlanır ve anlaşmalı kargo firması ile
  gönderilir. Ödemenizin tamamlanmasının ardından siparişiniz <strong>3 (üç) iş günü</strong> içinde
  kapınızda olacak şekilde teslim edilir. (Yasal azami süre 30 gündür; hedefimiz 3 iş günüdür.)</p>

  <h2>Kargo Ücreti</h2>
  <p><?= config('shipping_fee') ? 'Kargo ücreti sipariş özetinde belirtilir.' : 'Türkiye içi teslimatlarda kargo ücretsizdir.' ?>
  Ürünler, taşıma sırasında korunacak şekilde özenli ve güvenli biçimde paketlenir.</p>

  <h2>Teslim Alma</h2>
  <ul>
    <li>Kargoyu teslim alırken paketi kontrol ediniz. Hasarlı görünüyorsa tutanak tutturarak teslim almayınız ve <?= e($c['email']) ?> adresine bildiriniz.</li>
    <li>Teslimat, sipariş formunda belirttiğiniz adrese yapılır. Adres bilgisinin doğruluğundan alıcı sorumludur.</li>
  </ul>

  <h2>Takip</h2>
  <p>Siparişiniz kargoya verildiğinde takip bilgisi e-posta ile paylaşılır. Sorularınız için:
  <a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a>.</p>

  <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--ghost btn--sm">← Koleksiyona dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
