<?php
/** Sık Sorulan Sorular (SSS) — FAQPage JSON-LD */
$PAGE_TITLE = 'Sık Sorulan Sorular — Atölye RA';
$PAGE_DESC  = 'Atölye RA hakkında sık sorulan sorular: kumaş, beden, teslimat, ödeme, iade.';
require __DIR__ . '/partials/header.php';

$faq = [
  ['Ürünler hangi kumaştan üretiliyor?',
   'Sanat baskı koleksiyonumuz ipek saten, saten, viskon ve viskon şifon üzerine hazırlanır. Tılsım serisi ise TENCEL üzerine nakışla, 30’da bir üretilen sınırlı bir edisyondur.'],
  ['Bedenler nasıl? Tek beden mi?',
   'Kimono ve sabahlıklarımız tek beden (free size) olarak, geniş ve zarif bir kalıpla tasarlanır; farklı bedenlere rahatça uyum sağlar.'],
  ['Teslimat ne kadar sürer?',
   'Siparişiniz tam (özel) paketleme ile hazırlanır ve 3 iş günü içinde kapınızda olur.'],
  ['Nasıl ödeme yapabilirim?',
   'Havale/EFT ile ödeyebilirsiniz; kredi kartı ile ödeme de kısa süre içinde aktif olacaktır. Fiyatları TL, USD veya EUR olarak görüntüleyebilirsiniz.'],
  ['İade ve değişim mümkün mü?',
   'Evet. Ürünü teslim aldıktan sonra 14 gün içinde koşulsuz cayma hakkınız vardır. Detaylar İptal & İade sayfamızda.'],
  ['Ürünleri yerinde görebilir miyim?',
   'Elbette. Parçalarımızı Beymen mağazalarında ve seçkin butiklerde görüp deneyebilirsiniz. Satış Noktaları sayfasından size en yakın yeri bulabilirsiniz.'],
  ['Her desen tek mi?',
   'Tılsım/TENCEL serisi sınırlı ve özeldir. Sanat baskı koleksiyonu ise seçkin kumaşlar üzerine özenle hazırlanır; her parça bir sanat eseri gibidir.'],
];
?>
<section class="page-hero">
  <p class="page-hero__kicker">Yardım</p>
  <h1 class="page-hero__title">Sık Sorulan Sorular</h1>
</section>

<div class="legal">
  <?php foreach ($faq as $f): ?>
    <h2><?= e($f[0]) ?></h2>
    <p><?= e($f[1]) ?></p>
  <?php endforeach; ?>
  <p style="margin-top:30px;">Başka sorunuz mu var? <a href="<?= e(wa_contact()) ?>" target="_blank" rel="noopener" style="color:var(--bordeaux);">WhatsApp'tan yazın</a> ya da <a href="mailto:<?= e(config('company.email')) ?>"><?= e(config('company.email')) ?></a>.</p>
</div>

<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question', 'name' => $f[0],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
  ], $faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
