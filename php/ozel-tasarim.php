<?php
/** Özel Üretim (Custom Made) — galeri, detay ve WhatsApp talep formu */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/custom-designs.php';

$cc        = custom_config();
$priceTxt  = custom_price_txt();
$havaleTxt = custom_price_havale_txt();
$WA        = (string)$cc['phone'];

/* --- Talep formu → WhatsApp'a yönlendir --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'ozel_talep') {
    $d     = custom_by_slug(preg_replace('/[^a-z0-9-]/', '', $_POST['slug'] ?? ''));
    $ad    = trim(strip_tags($_POST['ad'] ?? ''));
    $tel   = trim(strip_tags($_POST['telefon'] ?? ''));
    $yer   = trim(strip_tags($_POST['yerlesim'] ?? ''));
    $nakis = trim(strip_tags($_POST['nakis'] ?? ''));
    $not   = trim(strip_tags($_POST['not'] ?? ''));
    $tils  = array_map(fn($t) => trim(strip_tags($t)), (array)($_POST['tilsimlar'] ?? []));
    $dname = $d ? ($d['name'] . ' (' . $d['no'] . ')') : 'Özel Üretim';

    $lines = ["Merhaba Atölye RA 🌿 '{$dname}' özel üretim istiyorum."];
    if ($ad)    $lines[] = "İsim: {$ad}";
    if ($tel)   $lines[] = "Telefon: {$tel}";
    if ($tils)  $lines[] = "Tılsım: " . implode(', ', $tils);
    if ($yer)   $lines[] = "Tılsım yeri: {$yer}";
    if ($nakis) $lines[] = "Nakış (isim/şiir/yazı): {$nakis}";
    if ($not)   $lines[] = "Not: {$not}";
    $lines[] = "Fiyat: {$priceTxt} (havale ile {$havaleTxt}) · Teslim: {$cc['lead_time']}";

    redirect(wa_link($WA, implode("\n", $lines)));
}

$slug   = preg_replace('/[^a-z0-9-]/', '', $_GET['d'] ?? '');
$design = $slug ? custom_by_slug($slug) : null;
$tals   = custom_talismans();

/* ============================ DETAY ============================ */
if ($design):
    $tal = custom_talisman_by_no($design['charm']);
    $PAGE_TITLE = e($design['name']) . ' — Özel Üretim | Atölye RA';
    $PAGE_DESC  = mb_substr($design['teaser'] . ' Size özel kimono, ' . $priceTxt . ', ' . $cc['lead_time'] . '.', 0, 155);
    $OG_IMAGE   = site_url($design['img']);
    require __DIR__ . '/partials/header.php';
?>
<article class="cx">
  <div class="cx-detail">
    <div class="cx-detail__img">
      <picture>
        <source type="image/webp" srcset="<?= asset($design['img_webp']) ?>">
        <img src="<?= asset($design['img']) ?>" alt="<?= e($design['name']) ?> — Atölye RA özel üretim" loading="eager" fetchpriority="high">
      </picture>
      <?php if ($tal): ?><span class="cx-badge" title="<?= e($tal['name']) ?>"><?= talisman_svg($design['charm'], 'tsym') ?></span><?php endif; ?>
    </div>

    <div class="cx-detail__info">
      <p class="cx-kicker"><a href="<?= url('/ozel-uretim') ?>" style="color:inherit;">Özel Üretim</a> · <?= e($design['no']) ?></p>
      <h1 class="cx-title"><?= e($design['name']) ?></h1>
      <p class="cx-price"><?= e($priceTxt) ?> <span>· havale ile <?= e($havaleTxt) ?> · teslim <?= e($cc['lead_time']) ?></span></p>
      <p class="cx-story"><?= e($design['story']) ?></p>

      <?php if ($tal): ?>
      <div class="cx-tal-card">
        <span class="cx-tal-card__sym"><?= talisman_svg($design['charm'], 'tsym tsym--lg') ?></span>
        <div>
          <p class="cx-tal-card__lbl">Bu tasarıma eşlik eden tılsım</p>
          <p class="cx-tal-card__nm"><?= e($tal['no']) ?> · <?= e($tal['name']) ?></p>
          <p class="cx-tal-card__mn"><?= e($tal['meaning']) ?></p>
          <p class="cx-tal-card__note">Dilerseniz başka bir tılsım seçebilir, dilediğiniz yere işletebilirsiniz.</p>
        </div>
      </div>
      <?php endif; ?>

      <ul class="cx-spec">
        <li><span>Kalıp</span> Standart bayan kimono kalıbından, <strong>kişiye özel ölçü</strong> ile.</li>
        <li><span>Gizli cep</span> İçinde <strong>dışarıdan görünmeyen ince fermuarlı gizli bir cep</strong> — telefon ve 2 kredi kartı düşmeden, güvende (plajda bile).</li>
        <li><span>Tılsım</span> Dilediğiniz tılsımı <strong>dilediğiniz yere</strong> işliyoruz (sırt, ön, yaka, kol, etek…).</li>
        <li><span>Nakış</span> İsminizi, size özel bir şiiri ya da bir cümleyi <strong>elde nakışlıyoruz</strong>.</li>
        <li><span>Kargo</span> Adresinize <strong>ücretsiz</strong> kargo.</li>
        <li><span>Teslim</span> Siparişten sonra <?= e($cc['lead_time']) ?> içinde elinizde.</li>
      </ul>

      <form method="post" action="<?= url('/ozel-uretim') ?>" class="cx-form">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="ozel_talep">
        <input type="hidden" name="slug" value="<?= e($design['slug']) ?>">
        <h2 class="cx-form__h">Bu tasarımı iste</h2>
        <div class="cx-row">
          <label>Adınız<input type="text" name="ad" required placeholder="Ad Soyad"></label>
          <label>Telefon<input type="tel" name="telefon" required placeholder="05xx xxx xx xx"></label>
        </div>
        <p class="cx-form__lbl">Tılsım seçin <small>(bir veya birkaç · şekilleri aşağıda)</small></p>
        <div class="cx-chips">
          <?php foreach ($tals as $t): ?>
            <label class="cx-chip" title="<?= e($t['meaning']) ?>">
              <input type="checkbox" name="tilsimlar[]" value="<?= e($t['no'] . ' ' . $t['name']) ?>" <?= $t['no'] === ($tal['no'] ?? '') ? 'checked' : '' ?>>
              <span><?= talisman_svg(substr($t['no'], -2), 'tsym tsym--sm') ?><?= e($t['name']) ?></span>
            </label>
          <?php endforeach; ?>
        </div>
        <label class="cx-full">Tılsımı nereye işleyelim?<input type="text" name="yerlesim" placeholder="örn. sırta ve yakaya"></label>
        <label class="cx-full">Nakış — isim, şiir veya bir cümle<input type="text" name="nakis" placeholder="örn. adınız · sevdiğiniz bir dize · özel bir espri"></label>
        <label class="cx-full">Eklemek istedikleriniz<textarea name="not" rows="3" placeholder="Renk tercihi, hediye notu, özel bir dilek…"></textarea></label>
        <button type="submit" class="btn btn--solid cx-submit">WhatsApp ile Talep Gönder</button>
        <p class="cx-note">Formu gönderince WhatsApp açılır; tüm seçimleriniz hazır mesaj olarak gelir. Fikirleriniz bizim için çok değerli — birlikte netleştirip üretime alırız.</p>
      </form>

      <p class="cx-surprise">✦ Görseller birer <strong>soyut sanat eseri</strong>dir. Kimononuz bu ruhu taşıyan, yüksek çözünürlüklü ve uyumlu bir yorumu olacaktır — yani biraz da <strong>sürpriz</strong>. (Bu, tılsımlar için geçerli değildir: tılsımı tam istediğiniz yere işleriz.)</p>
    </div>
  </div>

  <?php $others = array_values(array_filter(custom_designs(), fn($x) => $x['slug'] !== $design['slug'])); shuffle($others); $others = array_slice($others, 0, 3); ?>
  <div class="cx-more">
    <p class="cx-kicker" style="text-align:center;">Diğer Tasarımlar</p>
    <div class="cx-grid cx-grid--3">
      <?php foreach ($others as $o) { $ot = custom_talisman_by_no($o['charm']); ?>
        <a class="cx-card" href="<?= url('/ozel-uretim?d=' . $o['slug']) ?>">
          <div class="cx-card__img"><picture><source type="image/webp" srcset="<?= asset($o['img_webp']) ?>"><img src="<?= asset($o['img']) ?>" alt="<?= e($o['name']) ?>" loading="lazy"></picture><span class="cx-badge"><?= talisman_svg($o['charm']) ?></span></div>
          <div class="cx-card__cap"><span class="cx-card__no"><?= e($o['no']) ?></span><span class="cx-card__nm"><?= e($o['name']) ?></span><span class="cx-card__pr"><?= e($priceTxt) ?></span></div>
        </a>
      <?php } ?>
    </div>
  </div>
</article>
<?php
  require __DIR__ . '/partials/footer.php';
  return;
endif;

/* ============================ GALERİ ============================ */
$PAGE_TITLE = 'Özel Üretim — Kişiye Özel, Tek Nüsha Kimono | Atölye RA';
$PAGE_DESC  = 'Bir sanat eserini seçin; kişiye özel ölçü, dilediğiniz tılsım, isim/şiir nakışı, gizli cep ve ücretsiz kargo ile size özel üretelim. ' . $priceTxt . ' · havale ile ' . $havaleTxt . '.';
$OG_IMAGE   = site_url('images/custom/custom-05.jpg');
require __DIR__ . '/partials/header.php';
$designs = custom_designs();
?>
<section class="cx-hero">
  <p class="cx-kicker">Özel Üretim</p>
  <h1 class="cx-hero__title">Dünyada Tek. Sadece Senin.</h1>
  <p class="cx-hero__sub">Biz sadece hazır giysi satmıyoruz. Aşağıdaki sanat eserlerinden birini seçin; standart bayan kimono kalıbımızdan <strong>kişiye özel ölçüyle</strong> üretelim, tılsımınızı işleyelim, adınızı ya da size özel bir dizeyi nakışlayalım. Elinize geçen parça dünyada bir tane olacak — sizin gibi.</p>
  <p class="cx-hero__meta"><span><?= e($priceTxt) ?></span><em>havale ile <?= e($havaleTxt) ?> · teslim <?= e($cc['lead_time']) ?> · ücretsiz kargo</em></p>
</section>

<section class="cx-why">
  <div><h3>Tek Nüsha</h3><p>Her tasarımdan yalnızca bir adet. Sizin ölçünüze, sizin için.</p></div>
  <div><h3>Gizli Cep</h3><p>Dışarıdan görünmeyen ince fermuarlı gizli cep; telefon ve 2 kredi kartı düşmeden güvende — plajda bile.</p></div>
  <div><h3>Nakış</h3><p>İsminiz, sevdiğiniz bir şiir ya da size özel bir cümle; elde nakışlanır.</p></div>
  <div><h3>Tılsım</h3><p>Dilediğiniz tılsımı, kimononun dilediğiniz yerine işleriz.</p></div>
  <div><h3>Ücretsiz Kargo</h3><p>Adresinize, tam (özel) paketleme ile ücretsiz gönderilir.</p></div>
  <div><h3>Havale ile %<?= (int)$cc['discount_pct'] ?></h3><p>Siteden havale/EFT ile ödeyene her gün %<?= (int)$cc['discount_pct'] ?> indirim.</p></div>
</section>

<section class="cx-steps">
  <div><span>01</span><h3>Tasarımını seç</h3><p>Aşağıdaki 12 sanat eserinden kalbine dokunanı seç.</p></div>
  <div><span>02</span><h3>Tılsımını & nakışını söyle</h3><p>Tılsımı istediğin yere; adını ya da bir dizeyi nakışa.</p></div>
  <div><span>03</span><h3><?= e($cc['lead_time']) ?>da kapında</h3><p>Elde işlenmiş kimononuz ücretsiz kargoyla adresinizde.</p></div>
</section>

<section class="cx-grid-wrap">
  <div class="cx-grid">
    <?php foreach ($designs as $d): $t = custom_talisman_by_no($d['charm']); ?>
      <a class="cx-card" href="<?= url('/ozel-uretim?d=' . $d['slug']) ?>">
        <div class="cx-card__img">
          <picture><source type="image/webp" srcset="<?= asset($d['img_webp']) ?>"><img src="<?= asset($d['img']) ?>" alt="<?= e($d['name']) ?> — özel üretim kimono" loading="lazy"></picture>
          <span class="cx-badge" title="Tılsım: <?= e($t['name'] ?? '') ?>"><?= talisman_svg($d['charm']) ?></span>
        </div>
        <div class="cx-card__cap">
          <span class="cx-card__no"><?= e($d['no']) ?></span>
          <span class="cx-card__nm"><?= e($d['name']) ?></span>
          <span class="cx-card__ts"><?= e($d['teaser']) ?></span>
          <span class="cx-card__pr"><?= e($priceTxt) ?></span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="cx-tals-wrap">
  <p class="cx-kicker" style="text-align:center;">Tılsımlar</p>
  <h2 class="cx-h2">Kimonona Hangi Duayı İşleyelim?</h2>
  <p class="cx-hero__sub" style="text-align:center;">Aşağıdaki tılsımlardan dilediğini seç; tasarımın formunda işaretle, nereye işleneceğini birlikte belirleyelim.</p>
  <ul class="cx-tals cx-tals--grid">
    <?php foreach ($tals as $t): ?>
      <li><span class="cx-tals__sym"><?= talisman_svg(substr($t['no'], -2), 'tsym tsym--md') ?></span><span class="cx-tals__tx"><strong><?= e($t['no']) ?> · <?= e($t['name']) ?></strong><span><?= e($t['meaning']) ?></span></span></li>
    <?php endforeach; ?>
  </ul>
</section>

<section class="cx-nakis">
  <div class="cx-nakis__in">
    <p class="cx-kicker">Nakış & Kişiselleştirme</p>
    <h2 class="cx-h2" style="text-align:left;">Madem Bir Tane Üretiyoruz…</h2>
    <p>Ona da size özel dokunalım. İsminizi, sevdiğiniz bir şiiri, bir şairin dizesini, kısacık bir espriyi ya da yalnızca ikinizin anlayacağı bir cümleyi elde nakışlayabiliriz.</p>
    <ul>
      <li>Hediye mi? Sevdiğiniz kişinin ismini veya ona yazılmış birkaç satırı işleyelim.</li>
      <li>Kendinize mi? Sizi anlatan bir söz, bir tarih, bir niyet…</li>
      <li>Fikirleriniz bizim için çok değerli — birlikte tasarlayalım.</li>
    </ul>
    <p class="cx-nakis__hint">Aklınızdakini yazmanız yeterli; gerisini biz halledelim.</p>
    <a class="btn btn--solid" href="<?= e(wa_link($WA, 'Merhaba Atölye RA 🌿 Özel üretim kimono ve nakış (isim/şiir) hakkında bilgi almak istiyorum.')) ?>" target="_blank" rel="noopener">WhatsApp'tan Yazın</a>
  </div>
</section>

<section class="cx-info">
  <h2 class="cx-h2">Bilmeniz Gerekenler</h2>
  <ul class="cx-info__list">
    <li><strong>Soyut görseller:</strong> Bunlar birer sanat eseridir. Kimononuz bu ruhu taşıyan, yüksek çözünürlüklü ve uyumlu bir yorumu olacaktır — yani biraz da güzel bir sürpriz.</li>
    <li><strong>Tılsımlar hariç:</strong> Sürpriz yalnızca desen içindir. Tılsımı tam istediğiniz yere, istediğiniz gibi işleriz.</li>
    <li><strong>Gizli cep:</strong> İçeride dışarıdan görünmeyen ince fermuarlı bir cep; telefon ve 2 kredi kartı düşmeden güvende.</li>
    <li><strong>Ücretsiz kargo:</strong> Adresinize ücretsiz gönderilir.</li>
    <li><strong>Havale ile %<?= (int)$cc['discount_pct'] ?>:</strong> Siteden havale/EFT ile ödeyene her gün %<?= (int)$cc['discount_pct'] ?> indirim.</li>
    <li><strong>Teslim:</strong> Siparişten sonra <?= e($cc['lead_time']) ?> içinde.</li>
  </ul>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
