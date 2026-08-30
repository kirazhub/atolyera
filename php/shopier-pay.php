<?php
/** Shopier ödeme sayfası — siparişi Shopier'e gönderir (auto-submit) */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/shopier.php';

$code = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['order'] ?? '');
if (!$code || !shopier_enabled()) { flash_set('error', 'Ödeme başlatılamadı.'); redirect('/sepet'); }

$st = db()->prepare('SELECT * FROM orders WHERE order_code = ?');
$st->execute([$code]); $order = $st->fetch();
if (!$order || $order['payment_status'] === 'odendi') { redirect('/'); }

$its = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $its->execute([$order['id']]);
$items = $its->fetchAll();

$fields = shopier_form_fields($order, $items);
?><!DOCTYPE html><html lang="tr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow"><title>Ödemeye yönlendiriliyorsunuz… — Atölye RA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300&family=Jost:wght@300;400&display=swap" rel="stylesheet">
<style>
  body{font-family:'Jost',system-ui,sans-serif;background:#F5F1EA;color:#1A1A1A;display:flex;
    min-height:100vh;align-items:center;justify-content:center;text-align:center;margin:0;padding:24px;}
  h1{font-family:'Fraunces',serif;font-weight:300;font-size:26px;margin:0 0 10px;}
  p{color:#5C1A1B;letter-spacing:.1em;font-size:13px;}
  .spin{width:34px;height:34px;border:2px solid #d8d2c6;border-top-color:#5C1A1B;border-radius:50%;
    margin:20px auto;animation:r 1s linear infinite;}
  @keyframes r{to{transform:rotate(360deg)}}
</style></head><body>
  <div>
    <h1>Güvenli ödemeye yönlendiriliyorsunuz</h1>
    <div class="spin"></div>
    <p>LÜTFEN BEKLEYİN…</p>
    <form id="shopierForm" method="post" action="<?= e(shopier_endpoint()) ?>">
      <?php foreach ($fields as $k => $v): ?>
        <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
      <?php endforeach; ?>
      <noscript><button type="submit">Ödemeye devam et</button></noscript>
    </form>
  </div>
  <script>document.getElementById('shopierForm').submit();</script>
</body></html>
