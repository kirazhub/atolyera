<?php
/** İletişim / Kurumsal bilgiler */
$PAGE_TITLE = 'İletişim — Atölye RA';
$PAGE_DESC  = 'Atölye RA iletişim ve kurumsal bilgiler.';
require __DIR__ . '/partials/header.php';
$c = config('company');
?>
<section class="page-hero">
  <p class="page-hero__kicker">Bize Ulaşın</p>
  <h1 class="page-hero__title">İletişim</h1>
  <p class="page-hero__intro">Sorularınız, siparişleriniz ve iş birlikleri için bize yazın.</p>
</section>

<div class="legal">
  <h2>İletişim Bilgileri</h2>
  <p>
    <strong>E-posta:</strong> <a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a><br>
    <?php if (!empty($c['phone'])): ?><strong>Telefon / WhatsApp:</strong> <a href="<?= e(wa_contact()) ?>" target="_blank" rel="noopener"><?= e($c['phone']) ?></a><br><?php endif; ?>
    <strong>Web:</strong> atolyera.com
  </p>

  <h2>Kurumsal Bilgiler</h2>
  <p>
    <strong>Ünvan:</strong> <?= e($c['title']) ?><br>
    <strong>Adres:</strong> <?= e($c['address']) ?><br>
    <strong>Vergi Dairesi / No:</strong> <?= e($c['tax_office']) ?> / <?= e($c['tax_no']) ?><br>
    <?php if (!empty($c['mersis'])): ?><strong>MERSİS No:</strong> <?= e($c['mersis']) ?><br><?php endif; ?>
  </p>

  <h2>Nereden Alabilirim?</h2>
  <p>Online alışveriş için <a href="<?= url('/urunler') ?>">koleksiyonu</a> inceleyebilir; parçaları yerinde görmek için
  <a href="<?= url('/satis-noktalari') ?>">Beymen ve butik mağazalarımıza</a> uğrayabilirsiniz.</p>

  <h2>Teslimat & İade</h2>
  <p>Tam paketleme ile 3 iş günü içinde teslim. Detaylar:
  <a href="<?= url('/yasal/teslimat') ?>">Teslimat</a> ·
  <a href="<?= url('/yasal/iade-cayma') ?>">İptal &amp; İade</a> ·
  <a href="<?= url('/yasal/mesafeli-satis') ?>">Mesafeli Satış Sözleşmesi</a>.</p>

  <p style="margin-top:30px;">
    <a href="<?= e(wa_contact()) ?>" target="_blank" rel="noopener" class="btn wa-btn">WhatsApp'tan Yazın</a>
    &nbsp; <a href="mailto:<?= e($c['email']) ?>" class="btn btn--ghost">E-posta Gönderin</a>
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
