<?php
/** 404 */
require_once __DIR__ . '/includes/bootstrap.php';
http_response_code(404);
$PAGE_TITLE = 'Sayfa Bulunamadı — Atölye RA';
require __DIR__ . '/partials/header.php';
?>
<div class="shell shell--narrow" style="text-align:center;">
  <p class="page-hero__kicker">404</p>
  <h1 class="page-hero__title" style="font-size:clamp(30px,5vw,48px);">Bu desen bulunamadı</h1>
  <p style="font-family:var(--serif-text);font-size:19px;color:var(--graphite-soft);margin:18px 0 30px;">Aradığınız sayfa yerinde değil. Koleksiyona dönelim.</p>
  <a href="<?= url('/urunler') ?>" class="btn btn--solid">Koleksiyon</a>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
