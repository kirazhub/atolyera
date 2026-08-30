<?php
$PAGE_TITLE = 'Ön Bilgilendirme Formu — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>Ön Bilgilendirme Formu</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <h2>Satıcı Bilgileri</h2>
  <p>Ünvan: <?= e($c['title']) ?><br>
     Adres: <?= e($c['address']) ?><br>
     Vergi Dairesi / No: <?= e($c['tax_office']) ?> / <?= e($c['tax_no']) ?><br>
     E-posta: <?= e($c['email']) ?><?php if ($c['phone']): ?> · Telefon: <?= e($c['phone']) ?><?php endif; ?></p>

  <h2>Ürün ve Fiyat</h2>
  <p>Satışa konu ürünlerin temel nitelikleri (isim, kumaş, beden, adet) ile KDV dahil satış fiyatı,
  sipariş sırasında sepet ve sipariş özeti ekranında ve sipariş onay e-postasında yer alır.
  Fiyatlar, güncelleme yapılana kadar geçerlidir.</p>

  <h2>Ödeme Şekli</h2>
  <p>Ödeme, havale/EFT veya SATICI'nın sunduğu güvenli ödeme yöntemleriyle yapılır. Sipariş sonrası
  ödeme talimatları ALICI'ya iletilir.</p>

  <h2>Teslimat</h2>
  <p>Ürün, ALICI'nın bildirdiği adrese anlaşmalı kargo ile, yasal azami 30 gün içinde teslim edilir.
  Teslimat koşulları için <a href="<?= url('/yasal/teslimat') ?>">Teslimat</a> sayfasına bakınız.</p>

  <h2>Cayma Hakkı</h2>
  <p>ALICI, ürünün tesliminden itibaren <strong>14 gün</strong> içinde cayma hakkına sahiptir. Ayrıntılar
  <a href="<?= url('/yasal/iade-cayma') ?>">İptal &amp; İade</a> ve
  <a href="<?= url('/yasal/mesafeli-satis') ?>">Mesafeli Satış Sözleşmesi</a> sayfalarında yer alır.</p>

  <h2>Şikâyet ve İtiraz</h2>
  <p>ALICI, talep ve şikâyetlerini <?= e($c['email']) ?> adresine iletebilir; ayrıca yerleşim yerindeki
  Tüketici Hakem Heyeti veya Tüketici Mahkemesine başvurabilir.</p>

  <p style="margin-top:30px;"><a href="<?= url('/odeme') ?>" class="btn btn--ghost btn--sm">← Ödemeye dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
