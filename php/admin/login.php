<?php
/** Admin giriş */
require_once __DIR__ . '/_auth.php';
if (admin_logged_in()) redirect('/admin/');

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) $err = 'Oturum doğrulanamadı.';
    elseif (admin_login(trim($_POST['user'] ?? ''), $_POST['pass'] ?? '')) redirect('/admin/');
    else $err = 'Kullanıcı adı veya şifre hatalı.';
}
?><!DOCTYPE html><html lang="tr"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow"><title>Giriş — Atölye RA Yönetim</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<link rel="stylesheet" href="<?= asset('css/shop.css') ?>">
</head><body class="admin-body">
<div class="login-card">
  <h1>Atölye RA</h1>
  <?php if ($err): ?><div class="flash flash--error" style="margin-bottom:16px;"><?= e($err) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="field" style="margin-bottom:14px;"><label>Kullanıcı</label><input type="text" name="user" value="admin" required></div>
    <div class="field" style="margin-bottom:20px;"><label>Şifre</label><input type="password" name="pass" required></div>
    <button class="btn btn--solid btn--wide" type="submit">Giriş</button>
  </form>
</div>
</body></html>
