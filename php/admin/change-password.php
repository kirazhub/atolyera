<?php
/** Admin şifre değiştir */
require_once __DIR__ . '/_auth.php';
admin_require();

$msg = ''; $type = 'error';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) $msg = 'Oturum doğrulanamadı.';
    else {
        $cur = $_POST['current'] ?? ''; $new = $_POST['new'] ?? ''; $rep = $_POST['repeat'] ?? '';
        if (!password_verify($cur, admin_pass_hash())) $msg = 'Mevcut şifre hatalı.';
        elseif (strlen($new) < 8) $msg = 'Yeni şifre en az 8 karakter olmalı.';
        elseif ($new !== $rep) $msg = 'Yeni şifreler eşleşmiyor.';
        else {
            db()->prepare("UPDATE settings SET value=? WHERE key='admin_pass'")
                ->execute([password_hash($new, PASSWORD_DEFAULT)]);
            $msg = 'Şifre güncellendi.'; $type = 'success';
        }
    }
}
admin_header('Şifre Değiştir');
?>
<h1>Şifre Değiştir</h1>
<?php if ($msg): ?><div class="flash flash--<?= e($type) ?>" style="margin-bottom:16px;max-width:420px;"><?= e($msg) ?></div><?php endif; ?>
<form method="post" style="max-width:420px;background:#fff;border:1px solid var(--line);padding:26px;">
  <?= csrf_field() ?>
  <div class="field" style="margin-bottom:14px;"><label>Mevcut Şifre</label><input type="password" name="current" required></div>
  <div class="field" style="margin-bottom:14px;"><label>Yeni Şifre (min 8)</label><input type="password" name="new" required></div>
  <div class="field" style="margin-bottom:18px;"><label>Yeni Şifre (tekrar)</label><input type="password" name="repeat" required></div>
  <button class="btn btn--solid btn--wide">Güncelle</button>
</form>
<?php admin_footer(); ?>
