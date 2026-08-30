<?php /** Ortak ALT parça: footer + scriptler */ ?>
  <footer class="footer">
    <img src="<?= asset('images/logo-dark.png') ?>" alt="Atölye RA" class="footer__logo-img">
    <p class="footer__nav">
      <a href="<?= url('/urunler') ?>">Koleksiyon</a> ·
      <a href="<?= url('/kategori/baski') ?>">Sanat Baskı</a> ·
      <a href="<?= url('/kategori/tilsim') ?>">Tılsım</a> ·
      <a href="<?= url('/kategori/seri') ?>">Tılsım Serisi</a> ·
      <a href="<?= url('/sepet') ?>">Sepet</a>
    </p>
    <p class="footer__nav footer__nav--legal">
      <a href="<?= url('/yasal/mesafeli-satis') ?>">Mesafeli Satış Sözleşmesi</a> ·
      <a href="<?= url('/yasal/on-bilgilendirme') ?>">Ön Bilgilendirme</a> ·
      <a href="<?= url('/yasal/iade-cayma') ?>">İptal &amp; İade</a> ·
      <a href="<?= url('/yasal/teslimat') ?>">Teslimat</a> ·
      <a href="<?= url('/yasal/gizlilik') ?>">Gizlilik</a> ·
      <a href="<?= url('/yasal/kvkk') ?>">KVKK</a> ·
      <a href="<?= url('/yasal/cerez') ?>">Çerez</a>
    </p>
    <p class="footer__line"><?= e(config('company.title')) ?></p>
    <p class="footer__fine">İpek değil, TENCEL. © <?= date('Y') ?> Atölye RA · atolyera.com</p>
  </footer>

  <script>
    // Mobil menü
    (function(){
      var b=document.getElementById('menuBtn'), n=document.getElementById('navLinks');
      if(b&&n) b.addEventListener('click',function(){n.classList.toggle('is-open');b.classList.toggle('is-open');});
    })();
    // Scroll ile üst barı koyulaştır
    (function(){
      var nav=document.getElementById('nav');
      function s(){ if(!nav)return; nav.classList.toggle('is-scrolled', window.scrollY>40); }
      s(); window.addEventListener('scroll', s, {passive:true});
    })();
  </script>
</body>
</html>
