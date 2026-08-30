<?php
/** Hakkımızda — marka hikâyesi */
$PAGE_TITLE = 'Hakkımızda — Atölye RA';
$PAGE_DESC  = 'Atölye RA: yağlı boya tablolardan doğan desenler, elde işlenmiş kumaşlar. Giyilebilir sanat. Aynısı bir daha doğmaz.';
require __DIR__ . '/partials/header.php';
?>
<section class="page-hero">
  <p class="page-hero__kicker">Giyilebilir Sanat</p>
  <h1 class="page-hero__title">Hakkımızda</h1>
  <p class="page-hero__intro">Bir tablo neden yalnızca duvarda kalsın? Atölye RA, sanatı sırtınıza taşır.</p>
</section>

<div class="legal">
  <h2>Aynısı bir daha doğmaz</h2>
  <p>Atölye RA, yağlı boya tablolardan esinlenen özgün desenleri giyilebilir birer esere dönüştürür.
  Her parça, usta terzilerin elinde tek tek işlenir; bir kimono, bir sabahlık ya da omuzlara atılan
  bir örtü — hepsi birer tuvaldir.</p>

  <h2>Kumaş: İpek Saten, Saten, Viskon ve TENCEL</h2>
  <p>Sanat baskı koleksiyonumuz <strong>ipek saten, saten, viskon ve viskon şifon</strong> üzerine hazırlanır;
  ışığı yansıtan, tene yumuşak dokunan kumaşlar. Tılsım serimiz ise <strong>TENCEL</strong> üzerine nakışla,
  <strong>30’da bir üretilen</strong> sınırlı bir edisyondur. Doğaya saygılı, nefes alan, zarif.</p>

  <h2>Tılsımlar</h2>
  <p>Her tasarımın içinde bir niyet saklıdır — koruyan bir göz, bir denge hilali, açılan bir lotus…
  Taşıdığınız desen yalnızca bir motif değil, size eşlik eden sessiz bir tılsımdır.</p>

  <h2>Nerede bulunur?</h2>
  <p>Parçalarımızı <a href="<?= url('/urunler') ?>">online koleksiyonumuzdan</a> inceleyebilir; yerinde görmek için
  <a href="<?= url('/satis-noktalari') ?>">Beymen ve seçkin butik mağazalarımıza</a> uğrayabilirsiniz.
  Tam paketleme ile <strong>3 iş günü</strong> içinde kapınızda.</p>

  <p style="margin-top:30px;">
    <a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyonu Keşfet</a>
    &nbsp; <a href="<?= e(wa_contact()) ?>" target="_blank" rel="noopener" class="btn wa-btn">WhatsApp'tan Yazın</a>
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
