<?php
/** iyzico ödeme sonucu (callback) — iyzico bu adrese token POST eder */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/iyzico.php';

$token = $_POST['token'] ?? ($_GET['token'] ?? '');
if (!$token) { flash_set('error', 'Ödeme bilgisi alınamadı.'); redirect('/sepet'); }

// Siparişi token ile bul
$st = db()->prepare('SELECT * FROM orders WHERE iyzico_token = ? ORDER BY id DESC LIMIT 1');
$st->execute([$token]);
$order = $st->fetch();
if (!$order) { flash_set('error', 'Sipariş bulunamadı.'); redirect('/sepet'); }

// Zaten ödendiyse doğrudan başarı sayfası
if ($order['payment_status'] === 'odendi') { redirect('/siparis-tamam?code=' . urlencode($order['order_code'])); }

$res = iyzico_retrieve($token);

if (!empty($res['paid'])) {
    db()->prepare("UPDATE orders SET payment_status='odendi', iyzico_payment_id=? WHERE id=?")
        ->execute([$res['paymentId'] ?? '', $order['id']]);

    // Kalemleri çek, e-postaları gönder
    $its = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $its->execute([$order['id']]);
    $items = $its->fetchAll();
    $sent = send_mail($order['email'], 'Ödemeniz Alındı — ' . $order['order_code'],
                      order_email_html($order, $items), $order['customer_name']);
    send_mail(config('order_notify_email'), 'Yeni Sipariş (Kart · Ödendi) — ' . $order['order_code'],
              order_notify_html($order, $items));
    send_whatsapp(order_summary_text($order, $items));
    if ($sent) db()->prepare('UPDATE orders SET mail_sent=1 WHERE id=?')->execute([$order['id']]);

    cart_clear();
    unset($_SESSION['pending_order']);
    $_SESSION['last_order'] = $order['order_code'];
    redirect('/siparis-tamam?code=' . urlencode($order['order_code']));
}

// Başarısız
db()->prepare("UPDATE orders SET payment_status='basarisiz' WHERE id=?")->execute([$order['id']]);
flash_set('error', 'Ödeme tamamlanamadı: ' . ($res['error'] ?? 'Kart ödemesi onaylanmadı.') . ' Tekrar deneyebilir veya havale ile ödeyebilirsiniz.');
redirect('/odeme');
