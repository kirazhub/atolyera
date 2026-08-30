<?php
/** Shopier ödeme sonucu (callback) — Shopier bu adrese POST eder */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/shopier.php';

$res = shopier_verify_callback($_POST);
$code = preg_replace('/[^A-Za-z0-9\-]/', '', $res['order_id'] ?? '');
if (!$code) { flash_set('error', 'Ödeme bilgisi alınamadı.'); redirect('/sepet'); }

$st = db()->prepare('SELECT * FROM orders WHERE order_code = ?');
$st->execute([$code]); $order = $st->fetch();
if (!$order) { flash_set('error', 'Sipariş bulunamadı.'); redirect('/sepet'); }

if ($order['payment_status'] === 'odendi') { redirect('/siparis-tamam?code=' . urlencode($code)); }

if (!empty($res['ok']) && !empty($res['paid'])) {
    db()->prepare("UPDATE orders SET payment_status='odendi', iyzico_payment_id=? WHERE id=?")
        ->execute([$res['payment_id'] ?? '', $order['id']]);
    $its = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $its->execute([$order['id']]);
    $items = $its->fetchAll();
    $sent = send_mail($order['email'], 'Ödemeniz Alındı — ' . $code, order_email_html($order, $items), $order['customer_name']);
    send_mail(config('order_notify_email'), 'Yeni Sipariş (Kart · Ödendi) — ' . $code, order_notify_html($order, $items));
    send_whatsapp(order_summary_text($order, $items));
    if ($sent) db()->prepare('UPDATE orders SET mail_sent=1 WHERE id=?')->execute([$order['id']]);
    cart_clear();
    unset($_SESSION['pending_order']);
    $_SESSION['last_order'] = $code;
    redirect('/siparis-tamam?code=' . urlencode($code));
}

db()->prepare("UPDATE orders SET payment_status='basarisiz' WHERE id=?")->execute([$order['id']]);
flash_set('error', 'Ödeme tamamlanamadı. Tekrar deneyebilir veya havale ile ödeyebilirsiniz.');
redirect('/odeme');
