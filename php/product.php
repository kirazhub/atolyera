<?php
/** Ürün detay (product) */
require_once __DIR__ . '/includes/bootstrap.php';
$slug = preg_replace('/[^a-z0-9-]/', '', $_GET['slug'] ?? '');
$p    = $slug ? product_by_slug($slug) : null;
if (!$p) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$PAGE_TITLE = e($p['name']) . ' — ' . e($p['no_label']) . ' | Atölye RA';
$PAGE_DESC  = mb_substr(trim(strip_tags($p['story'])), 0, 155);
$OG_IMAGE   = site_url('images/' . $p['image']);
require __DIR__ . '/partials/header.php';

$related = array_values(array_filter(products_all($p['cat_slug']), fn($x) => $x['id'] != $p['id']));
$related = array_slice($related, 0, 3);
?>
<article class="product">
  <div class="product__gallery">
    <picture>
      <source type="image/webp" srcset="<?= asset('images/' . $p['image_webp']) ?>">
      <img src="<?= asset('images/' . $p['image']) ?>" alt="<?= e($p['name']) ?> — Atölye RA"
           width="<?= (int)$p['width'] ?>" height="<?= (int)$p['height'] ?>" fetchpriority="high">
    </picture>
  </div>

  <div class="product__info">
    <p class="product__no"><?= e($p['no_label']) ?></p>
    <h1 class="product__name"><?= e($p['name']) ?></h1>
    <p class="product__price"><?= money($p['price']) ?></p>

    <?php if ($p['story']): ?><p class="product__story"><?= e($p['story']) ?></p><?php endif; ?>
    <?php if ($p['muse']): ?><p class="product__muse"><?= e($p['muse']) ?></p><?php endif; ?>
    <?php if ($p['charm']): ?>
      <p class="product__charm"><span><?= e($p['charm_label'] ?: 'Tılsım') ?></span><?= e($p['charm']) ?></p>
    <?php endif; ?>

    <ul class="product__spec">
      <li><span>Fiyat</span> <?= money($p['price']) ?></li>
      <li><span>Beden</span> <?= e($p['size']) ?></li>
      <?php if ($p['material']): ?><li><span>Kumaş</span> <?= e($p['material']) ?></li><?php endif; ?>
      <li><span>Üretim</span> Elde işlenmiş · sınırlı</li>
      <li><span>Satış</span> atolyera.com · <a href="<?= url('/satis-noktalari') ?>" style="color:#5C1A1B;">Beymen ve butik mağazalarda deneyin</a></li>
    </ul>

    <?php if (!empty($p['is_sold'])): ?>
      <p class="product__sold">✦ Sahibini Buldu</p>
      <p style="font-family:var(--serif-text);color:var(--graphite-soft);margin:0 0 18px;">Bu parça sahibine kavuştu. Benzer bir tasarım veya size özel üretim için bize yazın.</p>
      <a href="<?= e(wa_link(config('whatsapp.contact_phone'), "Merhaba Atölye RA 🌿 '".$p['name']."' benzeri veya bana özel bir tasarım rica ediyorum.")) ?>" target="_blank" rel="noopener" class="btn wa-btn">WhatsApp'tan Sor</a>
    <?php else: ?>
    <form method="post" action="<?= url('/sepet-islem') ?>" class="product__buy">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <div class="qty">
        <button type="button" data-step="-1" aria-label="Azalt">−</button>
        <input type="number" name="qty" value="1" min="1" max="9" inputmode="numeric">
        <button type="button" data-step="1" aria-label="Arttır">+</button>
      </div>
      <button type="submit" class="btn btn--solid">Sepete Ekle</button>
      <button type="submit" name="buynow" value="1" class="btn btn--bordo">Hemen Al</button>
    </form>

    <?php $waMsg = "Merhaba Atölye RA 🌿 '" . $p['name'] . "' (" . $p['no_label'] . ") eserini giymek istiyorum, bilgi alabilir miyim?"; ?>
    <a href="<?= e(wa_link(config('whatsapp.contact_phone'), $waMsg)) ?>" target="_blank" rel="noopener" class="btn wa-btn" style="margin-top:14px;">WhatsApp’tan Sor</a>
    <p class="product__ship">✦ Tam (özel) paketleme ile <strong>3 iş günü</strong> içinde kapınızda.</p>
    <?php endif; ?>

    <?php if ($p['fabric_note']): ?><p class="product__fabric"><?= e($p['fabric_note']) ?></p><?php endif; ?>
  </div>
</article>

<?php if ($related): ?>
<div class="grid-wrap" style="padding-top:0;">
  <div class="mini-head" style="margin-bottom:40px;">
    <p class="page-hero__kicker">Aynı Ruhtan</p>
    <h2 class="page-hero__title" style="font-size:clamp(24px,4vw,36px);">Bunları da Sevebilirsiniz</h2>
  </div>
  <div class="grid">
    <?php foreach ($related as $p) { include __DIR__ . '/partials/product-card.php'; } ?>
  </div>
</div>
<?php endif; ?>

<script>
  (function(){
    var box=document.querySelector('.qty'); if(!box) return;
    var inp=box.querySelector('input');
    box.querySelectorAll('button[data-step]').forEach(function(b){
      b.addEventListener('click',function(){
        var v=parseInt(inp.value||'1',10)+parseInt(b.dataset.step,10);
        inp.value=Math.min(9,Math.max(1,v));
      });
    });
  })();
</script>

<!-- Ürün JSON-LD -->
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Product',
  'name' => $p['name'],
  'image' => site_url('images/' . $p['image']),
  'description' => trim(strip_tags($p['story'])),
  'brand' => ['@type' => 'Brand', 'name' => 'Atölye RA'],
  'material' => $p['material'],
  'offers' => [
    '@type' => 'Offer',
    'price' => (string)$p['price'],
    'priceCurrency' => config('currency_code'),
    'availability' => 'https://schema.org/InStock',
    'url' => site_url('urun/' . $p['slug']),
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
