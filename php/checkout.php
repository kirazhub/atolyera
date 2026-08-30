<?php
/** Ödeme / sipariş (checkout) */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/iyzico.php';

$items = cart_items();
if (!$items) { flash_set('info', 'Sepetiniz boş.'); redirect('/sepet'); }

$cardOn = iyzico_enabled();
$bankOn = (bool)config('payment.bank_enabled');

$errors = [];
$old = ['name'=>'','email'=>'','phone'=>'','address'=>'','city'=>'','note'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $errors['form'] = 'Oturum doğrulanamadı, tekrar deneyin.'; }
    foreach ($old as $k => $_) $old[$k] = trim($_POST[$k] ?? '');
    $method = ($_POST['method'] ?? '') === 'card' ? 'card' : 'bank';
    if ($method === 'card' && !$cardOn) $method = 'bank';
    if ($method === 'bank' && !$bankOn && $cardOn) $method = 'card';

    if ($old['name'] === '')  $errors['name'] = 'Ad soyad gerekli.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Geçerli bir e-posta girin.';
    if ($old['phone'] === '') $errors['phone'] = 'Telefon gerekli.';
    if ($old['address'] === '') $errors['address'] = 'Adres gerekli.';
    if ($old['city'] === '') $errors['city'] = 'Şehir gerekli.';
    if (empty($_POST['contract'])) $errors['contract'] = 'Mesafeli satış sözleşmesini onaylamalısınız.';
    if (empty($_POST['kvkk']))     $errors['kvkk'] = 'KVKK aydınlatma metnini onaylamalısınız.';

    if (!$errors) {
        $subtotal = cart_subtotal();
        $shipping = (int)config('shipping_fee', 0);
        $total    = $subtotal + $shipping;
        $code     = new_order_code();
        $payStatus = ($method === 'card') ? 'bekliyor' : 'havale-bekliyor';
        $cur   = current_currency();
        $rate  = ($cur === 'TRY') ? 1.0 : (float)(fx_rates()[$cur] ?? 1);
        $dispTotal = fx_convert((float)$total, $cur);

        $pdo = db();
        $pdo->beginTransaction();
        try {
            $st = $pdo->prepare('INSERT INTO orders
                (order_code,created_at,customer_name,email,phone,address,city,note,subtotal,shipping,total,status,kvkk,contract,payment_method,payment_status,display_currency,fx_rate,display_total)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,1,1,?,?,?,?,?)');
            $st->execute([$code, date('c'), $old['name'], $old['email'], $old['phone'],
                $old['address'], $old['city'], $old['note'], $subtotal, $shipping, $total, 'yeni', $method, $payStatus,
                $cur, $rate, $dispTotal]);
            $oid = (int)$pdo->lastInsertId();

            $ist = $pdo->prepare('INSERT INTO order_items
                (order_id,product_id,name,no_label,price,qty,line_total) VALUES (?,?,?,?,?,?,?)');
            foreach ($items as $it) {
                $ist->execute([$oid, $it['id'], $it['name'], $it['no_label'], $it['price'], $it['qty'], $it['line_total']]);
            }
            $pdo->commit();
        } catch (Throwable $ex) {
            $pdo->rollBack();
            error_log('[Atölye RA] Sipariş hatası: ' . $ex->getMessage());
            $errors['form'] = 'Sipariş kaydedilirken bir hata oluştu. Lütfen tekrar deneyin.';
        }

        if (!$errors) {
            $order = ['order_code'=>$code,'id'=>$oid,'customer_name'=>$old['name'],'email'=>$old['email'],
                      'phone'=>$old['phone'],'address'=>$old['address'],'city'=>$old['city'],
                      'note'=>$old['note'],'subtotal'=>$subtotal,'total'=>$total,
                      'payment_method'=>$method,'display_currency'=>$cur,'fx_rate'=>$rate,'display_total'=>$dispTotal];

            if ($method === 'card') {
                // iyzico Checkout Form başlat → ödeme sayfasına yönlendir (sepet ödeme sonrası temizlenir)
                $init = iyzico_init_checkout($order, $items);
                if (!empty($init['ok'])) {
                    db()->prepare('UPDATE orders SET iyzico_token=? WHERE id=?')->execute([$init['token'], $oid]);
                    $_SESSION['pending_order'] = $code;
                    redirect($init['url']);
                }
                db()->prepare("UPDATE orders SET payment_status='basarisiz' WHERE id=?")->execute([$oid]);
                $errors['form'] = 'Kart ödemesi başlatılamadı: ' . ($init['error'] ?? '') . ' Havale ile deneyebilirsiniz.';
            } else {
                // Havale/EFT — sipariş alındı, onay e-postaları + WhatsApp bildirimi
                $sent = send_mail($old['email'], 'Siparişiniz Alındı — ' . $code, order_email_html($order, $items), $old['name']);
                send_mail(config('order_notify_email'), 'Yeni Sipariş (Havale) — ' . $code, order_notify_html($order, $items));
                send_whatsapp(order_summary_text($order, $items));
                if ($sent) db()->prepare('UPDATE orders SET mail_sent=1 WHERE order_code=?')->execute([$code]);
                cart_clear();
                $_SESSION['last_order'] = $code;
                redirect('/siparis-tamam?code=' . urlencode($code));
            }
        }
    }
}

$PAGE_TITLE = 'Ödeme — Atölye RA';
$PAGE_DESC  = 'Sipariş bilgileri.';
require __DIR__ . '/partials/header.php';
?>
<div class="shell">
  <div class="mini-head" style="margin-bottom:34px;">
    <p class="page-hero__kicker">Son Adım</p>
    <h1 class="page-hero__title" style="font-size:clamp(30px,5vw,46px);">Sipariş Bilgileri</h1>
  </div>

  <?php if (!empty($errors['form'])): ?><div class="flash flash--error" style="margin-bottom:24px;"><?= e($errors['form']) ?></div><?php endif; ?>

  <form method="post" action="<?= url('/odeme') ?>" class="checkout-grid" novalidate>
    <?= csrf_field() ?>
    <div>
      <div class="form-grid">
        <div class="field field--full">
          <label>Ad Soyad</label>
          <input type="text" name="name" value="<?= e($old['name']) ?>" required>
          <?php if(!empty($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
          <label>E-posta</label>
          <input type="email" name="email" value="<?= e($old['email']) ?>" required>
          <?php if(!empty($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
          <label>Telefon</label>
          <input type="tel" name="phone" value="<?= e($old['phone']) ?>" required>
          <?php if(!empty($errors['phone'])): ?><span class="err"><?= e($errors['phone']) ?></span><?php endif; ?>
        </div>
        <div class="field field--full">
          <label>Adres</label>
          <textarea name="address" required><?= e($old['address']) ?></textarea>
          <?php if(!empty($errors['address'])): ?><span class="err"><?= e($errors['address']) ?></span><?php endif; ?>
        </div>
        <div class="field">
          <label>Şehir</label>
          <input type="text" name="city" value="<?= e($old['city']) ?>" required>
          <?php if(!empty($errors['city'])): ?><span class="err"><?= e($errors['city']) ?></span><?php endif; ?>
        </div>
        <div class="field field--full">
          <label>Sipariş Notu (opsiyonel)</label>
          <textarea name="note"><?= e($old['note']) ?></textarea>
        </div>
      </div>

      <div style="margin-top:26px;">
        <p style="font-family:var(--sans);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--bordeaux);margin:0 0 12px;">Ödeme Yöntemi</p>
        <?php $defCard = $cardOn; ?>
        <?php if ($cardOn): ?>
          <label class="check" style="align-items:center;">
            <input type="radio" name="method" value="card" <?= $defCard?'checked':'' ?>>
            <span><strong>Kredi / Banka Kartı</strong> — güvenli ödeme (iyzico). Kart bilgileri bizde saklanmaz.</span>
          </label>
        <?php endif; ?>
        <?php if ($bankOn): ?>
          <label class="check" style="align-items:center;">
            <input type="radio" name="method" value="bank" <?= !$defCard?'checked':'' ?>>
            <span><strong>Havale / EFT (IBAN)</strong> — sipariş sonrası IBAN bilgileri gösterilir; ödeme onayınca kargolanır.</span>
          </label>
        <?php endif; ?>
        <?php if (!$cardOn && !$bankOn): ?>
          <p style="font-family:var(--serif-text);color:var(--bordeaux);">Şu an çevrimiçi ödeme kapalı. Lütfen <?= e(config('company.email')) ?> ile iletişime geçin.</p>
        <?php endif; ?>
      </div>

      <div style="margin-top:22px;">
        <label class="check">
          <input type="checkbox" name="contract" value="1" <?= isset($_POST['contract'])?'checked':'' ?>>
          <span><a href="<?= url('/yasal/mesafeli-satis') ?>" target="_blank">Mesafeli Satış Sözleşmesi</a> ve
          <a href="<?= url('/yasal/on-bilgilendirme') ?>" target="_blank">Ön Bilgilendirme Formu</a>'nu okudum, onaylıyorum.</span>
        </label>
        <?php if(!empty($errors['contract'])): ?><span class="err"><?= e($errors['contract']) ?></span><?php endif; ?>
        <label class="check">
          <input type="checkbox" name="kvkk" value="1" <?= isset($_POST['kvkk'])?'checked':'' ?>>
          <span><a href="<?= url('/yasal/kvkk') ?>" target="_blank">KVKK Aydınlatma Metni</a>'ni okudum, kişisel verilerimin işlenmesini kabul ediyorum.</span>
        </label>
        <?php if(!empty($errors['kvkk'])): ?><span class="err"><?= e($errors['kvkk']) ?></span><?php endif; ?>
      </div>

      <button type="submit" class="btn btn--bordo btn--wide" style="margin-top:26px;">Siparişi Onayla</button>
      <p style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);margin-top:12px;line-height:1.6;">
        Kart ile ödemede güvenli iyzico sayfasına yönlendirilirsiniz. Havale seçerseniz sipariş sonrası IBAN bilgileri gösterilir.
      </p>
    </div>

    <aside class="order-box">
      <h3>Siparişiniz</h3>
      <?php foreach ($items as $it): ?>
        <div class="order-line">
          <span><?= e($it['name']) ?> × <?= (int)$it['qty'] ?></span>
          <span><?= money($it['line_total']) ?></span>
        </div>
      <?php endforeach; ?>
      <div class="order-line"><span>Kargo</span><span><?= config('shipping_fee') ? money(config('shipping_fee')) : 'Ücretsiz' ?></span></div>
      <div class="order-total"><span>Toplam</span><span><?= money(cart_total()) ?></span></div>
    </aside>
  </form>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
