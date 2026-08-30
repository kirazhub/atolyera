<?php
/** Sepet işlemleri (ekle/güncelle/çıkar/temizle) — POST */
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    flash_set('error', 'Oturum doğrulanamadı, lütfen tekrar deneyin.');
    redirect('/sepet');
}

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);
$qty    = max(1, (int)($_POST['qty'] ?? 1));

switch ($action) {
    case 'add':
        if (product_by_id($id)) { cart_add($id, $qty); flash_set('success', 'Parça sepete eklendi.'); }
        break;
    case 'update':
        cart_set($id, (int)($_POST['qty'] ?? 1));
        flash_set('info', 'Sepet güncellendi.');
        break;
    case 'remove':
        cart_remove($id); flash_set('info', 'Parça sepetten çıkarıldı.');
        break;
    case 'clear':
        cart_clear(); flash_set('info', 'Sepet boşaltıldı.');
        break;
}

// "Hemen Al" → doğrudan ödemeye
if (!empty($_POST['buynow']) && cart_count() > 0) redirect('/odeme');

redirect('/sepet');
