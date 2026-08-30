<?php /** Ortak ALT parça: footer + scriptler */ ?>
  <footer class="footer">
    <img src="<?= asset('images/logo-dark.png') ?>" alt="Atölye RA" class="footer__logo-img">
    <p class="footer__nav">
      <a href="<?= url('/urunler') ?>">Koleksiyon</a> ·
      <a href="<?= url('/kategori/baski') ?>">Sanat Baskı</a> ·
      <a href="<?= url('/kategori/tilsim') ?>">Tılsım</a> ·
      <a href="<?= url('/kategori/seri') ?>">Tılsım Serisi</a> ·
      <a href="<?= url('/hakkimizda') ?>">Hakkımızda</a> ·
      <a href="<?= url('/sss') ?>">SSS</a> ·
      <a href="<?= url('/satis-noktalari') ?>">Satış Noktaları</a> ·
      <a href="<?= url('/iletisim') ?>">İletişim</a> ·
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
    <p class="footer__fine">İpek saten, saten, viskon ve TENCEL üzerine giyilebilir sanat. © <?= date('Y') ?> Atölye RA · atolyera.com</p>
  </footer>

  <?php if (config('whatsapp.contact_enabled')): ?>
  <a class="wa-float" href="<?= e(wa_contact()) ?>" target="_blank" rel="noopener" aria-label="WhatsApp ile yazın">
    <svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><path fill="currentColor" d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.2 1.6 6L4 29l8.2-1.6c1.7.9 3.7 1.4 5.8 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.2-.4c-1-1.6-1.5-3.4-1.5-5.3C5 9.5 9.9 4.6 16 4.6S27 9.5 27 15 22.1 24.8 16 24.8zm5.5-7.4c-.3-.1-1.8-.9-2-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.5 0-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.3 5.2 4.6 2.9 1.2 2.9.8 3.5.8.5-.1 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.2-.6-.4z"/></svg>
  </a>
  <?php endif; ?>

  <div class="cookie" id="cookieBar" hidden>
    <span>Bu sitede yalnızca çalışması için gerekli çerezleri kullanıyoruz.
      <a href="<?= url('/yasal/cerez') ?>">Çerez Politikası</a>.</span>
    <button type="button" id="cookieOk">Tamam</button>
  </div>

  <script>
    // Mobil menü aç/kapa + link tıklanınca kapan
    (function(){
      var b=document.getElementById('menuBtn'), n=document.getElementById('navLinks');
      if(!b||!n) return;
      function close(){ n.classList.remove('is-open'); b.classList.remove('is-open'); document.body.classList.remove('nav-open'); }
      function toggle(){ n.classList.toggle('is-open'); b.classList.toggle('is-open'); document.body.classList.toggle('nav-open'); }
      b.addEventListener('click', toggle);
      // Menüdeki her linke basınca menü kapansın (yukarı kayıp kaybolsun)
      n.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', close); });
      // Menü açıkken sayfanın boş bir yerine dokununca da kapansın
      n.addEventListener('click', function(e){ if(e.target===n) close(); });
    })();
    // Scroll ile üst barı koyulaştır
    (function(){
      var nav=document.getElementById('nav');
      function s(){ if(!nav)return; nav.classList.toggle('is-scrolled', window.scrollY>40); }
      s(); window.addEventListener('scroll', s, {passive:true});
    })();
    // Çerez onayı
    (function(){
      try{
        var bar=document.getElementById('cookieBar'), ok=document.getElementById('cookieOk');
        if(!bar) return;
        if(!localStorage.getItem('ra_cookie_ok')) bar.hidden=false;
        if(ok) ok.addEventListener('click',function(){ localStorage.setItem('ra_cookie_ok','1'); bar.hidden=true; });
      }catch(e){}
    })();
  </script>
</body>
</html>
