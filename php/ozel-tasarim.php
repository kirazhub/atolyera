<?php
/** Sana Özel Tasarım (Custom Made) — galeri, detay ve talep formu */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/custom-designs.php';

$cc   = custom_config();
$priceTxt = number_format($cc['price_usd'], 0, ',', '.') . ' $';

/* --- Talep formu gönderimi → WhatsApp'a yönlendir --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'ozel_talep') {
    $d       = custom_by_slug(preg_replace('/[^a-z0-9-]/', '', $_POST['slug'] ?? ''));
    $ad      = trim(strip_tags($_POST['ad'] ?? ''));
    $tel     = trim(strip_tags($_POST['telefon'] ?? ''));
    $yer     = trim(strip_tags($_POST['yerlesim'] ?? ''));
    $not     = trim(strip_tags($_POST['not'] ?? ''));
    $tils    = array_map(fn($t) => trim(strip_tags($t)), (array)($_POST['tilsimlar'] ?? []));
    $dname   = $d ? ($d['name'] . ' (' . $d['no'] . ')') : 'Özel Tasarım';

    $lines = ["Merhaba Atölye RA 🌿 '{$dname}' özel tasarımını istiyorum."];
    if ($ad)  $lines[] = "İsim: {$ad}";
    if ($tel) $lines[] = "Telefon: {$tel}";
    if ($tils) $lines[] = "Seçtiğim tılsımlar: " . implode(', ', $tils);
    if ($yer) $lines[] = "Tılsım yerleşimi: {$yer}";
    if ($not) $lines[] = "Not: {$not}";
    $lines[] = "Fiyat: {$priceTxt} · Teslim: {$cc['lead_time']}";

    $wa = wa_link((string)config('whatsapp.contact_phone'), implode("\n", $lines));
    redirect($wa);
}

$slug   = preg_replace('/[^a-z0-9-]/', '', $_GET['d'] ?? '');
$design = $slug ? custom_by_slug($slug) : null;
$tals   = custom_talismans();

/* ============================ DETAY ============================ */
if ($design):
    $PAGE_TITLE = e($design['name']) . ' — Özel Tasarım | Atölye RA';
    $PAGE_DESC  = mb_substr($design['teaser'] . ' Size özel kimono, ' . $priceTxt . ', ' . $cc['lead_time'] . '.', 0, 155);
    $OG_IMAGE   = site_url($design['img']);
    require __DIR__ . '/partials/header.php';
?>
<article class="cx">
  <div class="cx-detail">
    <div class="cx-detail__img">
      <picture>
        <source type="image/webp" srcset="<?= asset($design['img_webp']) ?>">
        <img src="<?= asset($design['img']) ?>" alt="<?= e($design['name']) ?> — Atölye RA özel tasarım" loading="eager" fetchpriority="high">
      </picture>
    </div>

    <div class="cx-detail__info">
      <p class="cx-kicker"><a href="<?= url('/ozel-tasarim') ?>" style="color:inherit;">Sana Özel Tasarım</a> · <?= e($design['no']) ?></p>
      <h1 class="cx-title"><?= e($design['name']) ?></h1>
      <p class="cx-price"><?= e($priceTxt) ?> <span>· Teslim <?= e($cc['lead_time']) ?></span></p>
      <p class="cx-story"><?= e($design['story']) ?></p>

      <ul class="cx-spec">
        <li><span>Kalıp</span> Standart bayan kimono kalıbından, <strong>kişiye özel ölçü</strong> ile üretilir.</li>
        <li><span>Tılsım</span> Sitemizdeki tılsımları inceleyip <strong>dilediğiniz tılsımı, kimononun dilediğiniz yerine</strong> (sırt, ön, yaka, kol, etek…) yerleştiriyoruz.</li>
        <li><span>Fiyat</span> <?= e($priceTxt) ?></li>
        <li><span>Teslim</span> Siparişten sonra <?= e($cc['lead_time']) ?> içinde elinizde.</li>
        <li><span>Üretim</span> Elde işlenmiş · size özel · tek nüsha</li>
      </ul>

      <form method="post" action="<?= url('/ozel-tasarim') ?>" class="cx-form">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="ozel_talep">
        <input type="hidden" name="slug" value="<?= e($design['slug']) ?>">

        <h2 class="cx-form__h">Bu tasarımı iste</h2>

        <div class="cx-row">
          <label>Adınız<input type="text" name="ad" required placeholder="Ad Soyad"></label>
          <label>Telefon<input type="tel" name="telefon" required placeholder="05xx xxx xx xx"></label>
        </div>

        <p class="cx-form__lbl">Tılsım seçin <small>(bir veya birkaç — anlamları aşağıda)</small></p>
        <div class="cx-chips">
          <?php foreach ($tals as $t): ?>
            <label class="cx-chip">
              <input type="checkbox" name="tilsimlar[]" value="<?= e($t['no'] . ' ' . $t['name']) ?>">
              <span><?= e($t['name']) ?></span>
            </label>
          <?php endforeach; ?>
        </div>

        <label class="cx-full">Tılsımı nereye işleyelim?<input type="text" name="yerlesim" placeholder="örn. sırta ve yakaya"></label>
        <label class="cx-full">Eklemek istedikleriniz<textarea name="not" rows="3" placeholder="Renk tercihi, isim/nakış, özel bir dilek…"></textarea></label>

        <button type="submit" class="btn btn--solid cx-submit">WhatsApp ile Talep Gönder</button>
        <p class="cx-note">Formu gönderince WhatsApp açılır; tüm seçimleriniz hazır mesaj olarak gelir. Onaylayınca üretime alırız.</p>
      </form>

      <details class="cx-acc">
        <summary>Tılsımların anlamları</summary>
        <ul class="cx-tals">
          <?php foreach ($tals as $t): ?>
            <li><strong><?= e($t['no']) ?> · <?= e($t['name']) ?></strong> — <?= e($t['meaning']) ?></li>
          <?php endforeach; ?>
        </ul>
      </details>
    </div>
  </div>

  <?php $others = array_values(array_filter(custom_designs(), fn($x) => $x['slug'] !== $design['slug'])); shuffle($others); $others = array_slice($others, 0, 3); ?>
  <div class="cx-more">
    <p class="cx-kicker" style="text-align:center;">Diğer Tasarımlar</p>
    <div class="cx-grid cx-grid--3">
      <?php foreach ($others as $o): ?>
        <a class="cx-card" href="<?= url('/ozel-tasarim?d=' . $o['slug']) ?>">
          <div class="cx-card__img"><picture><source type="image/webp" srcset="<?= asset($o['img_webp']) ?>"><img src="<?= asset($o['img']) ?>" alt="<?= e($o['name']) ?>" loading="lazy"></picture></div>
          <div class="cx-card__cap"><span class="cx-card__no"><?= e($o['no']) ?></span><span class="cx-card__nm"><?= e($o['name']) ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</article>
<?php
  require __DIR__ . '/partials/footer.php';
  return;
endif;

/* ============================ GALERİ ============================ */
$PAGE_TITLE = 'Sana Özel Tasarım — Kişiye Özel Kimono | Atölye RA';
$PAGE_DESC  = 'Bir sanat eserini seçin, tılsımınızı dilediğiniz yere yerleştirelim; standart bayan kimono kalıbından size özel üretelim. ' . $priceTxt . ' · ' . $cc['lead_time'] . '.';
$OG_IMAGE   = site_url('images/custom/custom-05.jpg');
require __DIR__ . '/partials/header.php';
$designs = custom_designs();
?>
<section class="cx-hero">
  <p class="cx-kicker">Sana Özel Tasarım</p>
  <h1 class="cx-hero__title">Sadece Senin İçin, Tek Bir Nüsha</h1>
  <p class="cx-hero__sub">Aşağıdaki sanat eserlerinden birini seçin; standart bayan kimono kalıbımızdan, <strong>kişiye özel ölçüyle</strong> üretelim. Sitemizdeki tılsımlardan dilediğinizi seçip, kimononuzun dilediğiniz yerine biz işleyelim.</p>
  <p class="cx-hero__meta"><span><?= e($priceTxt) ?></span><em>Teslim: <?= e($cc['lead_time']) ?></em></p>
</section>

<section class="cx-steps">
  <div><span>01</span><h3>Tasarımını seç</h3><p>Aşağıdaki 12 sanat eserinden kalbine dokunanı seç.</p></div>
  <div><span>02</span><h3>Tılsımını yerleştir</h3><p>Tılsımlardan dilediğini seç; sırta, yakaya, kola… istediğin yere işleyelim.</p></div>
  <div><span>03</span><h3><?= e($cc['lead_time']) ?>da kapında</h3><p>Size özel, elde işlenmiş kimononuz <?= e($cc['lead_time']) ?> içinde teslim.</p></div>
</section>

<section class="cx-grid-wrap">
  <div class="cx-grid">
    <?php foreach ($designs as $d): ?>
      <a class="cx-card" href="<?= url('/ozel-tasarim?d=' . $d['slug']) ?>">
        <div class="cx-card__img">
          <picture><source type="image/webp" srcset="<?= asset($d['img_webp']) ?>"><img src="<?= asset($d['img']) ?>" alt="<?= e($d['name']) ?> — özel tasarım kimono" loading="lazy"></picture>
        </div>
        <div class="cx-card__cap">
          <span class="cx-card__no"><?= e($d['no']) ?></span>
          <span class="cx-card__nm"><?= e($d['name']) ?></span>
          <span class="cx-card__ts"><?= e($d['teaser']) ?></span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="cx-tals-wrap">
  <p class="cx-kicker" style="text-align:center;">Tılsımlar</p>
  <h2 class="cx-h2">Kimonona Hangi Duayı İşleyelim?</h2>
  <p class="cx-hero__sub" style="text-align:center;">Aşağıdaki tılsımlardan dilediğini seç; tasarımını seçtikten sonra formda işaretle, nereye işleneceğini birlikte belirleyelim.</p>
  <ul class="cx-tals cx-tals--grid">
    <?php foreach ($tals as $t): ?>
      <li><strong><?= e($t['no']) ?> · <?= e($t['name']) ?></strong><span><?= e($t['meaning']) ?></span></li>
    <?php endforeach; ?>
  </ul>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
