<?php
/** Admin ortak: oturum, yetki kontrolü ve panel şablonu */
require_once __DIR__ . '/../includes/bootstrap.php';

function admin_pass_hash(): string {
    $v = db()->query("SELECT value FROM settings WHERE key='admin_pass'")->fetchColumn();
    return $v ?: '';
}
function admin_logged_in(): bool { return !empty($_SESSION['admin']); }
function admin_require() {
    if (!admin_logged_in()) redirect('/admin/login.php');
}
function admin_login(string $user, string $pass): bool {
    if ($user !== config('admin.user')) return false;
    $h = admin_pass_hash();
    if ($h && password_verify($pass, $h)) { $_SESSION['admin'] = true; return true; }
    return false;
}

function admin_header(string $title) {
    $cur = basename($_SERVER['SCRIPT_NAME'] ?? '');
    ?><!DOCTYPE html><html lang="tr"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($title) ?> — Atölye RA Yönetim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&family=Fraunces:opsz,wght@9..144,300;9..144,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/shop.css') ?>">
    </head><body class="admin-body">
    <div class="admin-bar">
      <strong style="letter-spacing:.1em;">Atölye RA · Yönetim</strong>
      <a href="<?= url('/admin/') ?>" class="<?= $cur==='index.php'?'is-active':'' ?>">Özet</a>
      <a href="<?= url('/admin/orders.php') ?>" class="<?= $cur==='orders.php'||$cur==='order-view.php'?'is-active':'' ?>">Siparişler</a>
      <a href="<?= url('/admin/products.php') ?>" class="<?= $cur==='products.php'||$cur==='product-edit.php'?'is-active':'' ?>">Ürünler</a>
      <span class="spacer"></span>
      <a href="<?= url('/') ?>" target="_blank">Siteyi Gör ↗</a>
      <a href="<?= url('/admin/change-password.php') ?>">Şifre</a>
      <a href="<?= url('/admin/logout.php') ?>">Çıkış</a>
    </div>
    <div class="admin-wrap"><?php
    foreach (flash_all() as $f) echo '<div class="flash flash--'.e($f['type']).'" style="margin-bottom:18px;">'.e($f['msg']).'</div>';
}
function admin_footer() { echo '</div></body></html>'; }
