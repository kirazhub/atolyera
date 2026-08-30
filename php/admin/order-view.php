<?php
/** Admin tek sipariş + durum güncelle */
require_once __DIR__ . '/_auth.php';
admin_require();

$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT * FROM orders WHERE id=?'); $st->execute([$id]);
$o = $st->fetch();
if (!$o) { flash_set('error','Sipariş bulunamadı.'); redirect('/admin/orders.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $status = $_POST['status'] ?? $o['status'];
    $allowed = ['yeni','hazir','kargo','iptal'];
    if (in_array($status, $allowed, true)) {
        db()->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$status, $id]);
        flash_set('success','Sipariş durumu güncellendi.');
    }
    redirect('/admin/order-view.php?id='.$id);
}

$its = db()->prepare('SELECT * FROM order_items WHERE order_id=?'); $its->execute([$id]);
$items = $its->fetchAll();
admin_header('Sipariş ' . $o['order_code']);
?>
<p><a href="<?= url('/admin/orders.php') ?>" style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);">← Siparişler</a></p>
<h1><?= e($o['order_code']) ?> <span class="pill pill--<?= e($o['status']) ?>" style="font-size:13px;vertical-align:middle;"><?= e(ucfirst($o['status'])) ?></span></h1>

<div style="display:grid;grid-template-columns:1.4fr .9fr;gap:34px;align-items:start;">
  <div>
    <table class="admin-table">
      <thead><tr><th>Parça</th><th>Adet</th><th>Tutar</th></tr></thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr><td><?= e($it['name']) ?><br><span style="color:#999;font-size:12px;"><?= e($it['no_label']) ?></span></td>
        <td><?= (int)$it['qty'] ?></td><td><?= money($it['line_total']) ?></td></tr>
      <?php endforeach; ?>
        <tr><td colspan="2" style="text-align:right;"><strong>Toplam</strong></td><td><strong><?= money($o['total']) ?></strong></td></tr>
      </tbody>
    </table>
    <?php if ($o['note']): ?><p style="font-family:var(--serif-text);margin-top:16px;"><strong>Not:</strong> <?= nl2br(e($o['note'])) ?></p><?php endif; ?>
  </div>

  <div style="background:#fff;border:1px solid var(--line);padding:22px;">
    <h3 style="font-family:var(--serif-display);font-weight:400;margin:0 0 12px;">Müşteri</h3>
    <p style="font-family:var(--serif-text);font-size:16px;line-height:1.7;">
      <strong><?= e($o['customer_name']) ?></strong><br>
      <?= e($o['email']) ?><br><?= e($o['phone']) ?><br>
      <?= nl2br(e($o['address'])) ?><br><?= e($o['city']) ?>
    </p>
    <p style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);">Onay maili: <?= $o['mail_sent']?'gönderildi':'gönderilmedi (SMTP kapalı)' ?></p>

    <form method="post" style="margin-top:18px;">
      <?= csrf_field() ?>
      <div class="field"><label>Durum</label>
        <select name="status" style="padding:10px;border:1px solid var(--line);font-family:var(--sans);">
          <?php foreach (['yeni'=>'Yeni','hazir'=>'Hazırlanıyor','kargo'=>'Kargoda','iptal'=>'İptal'] as $k=>$v): ?>
            <option value="<?= $k ?>" <?= $o['status']===$k?'selected':'' ?>><?= $v ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button class="btn btn--solid btn--wide" style="margin-top:12px;">Güncelle</button>
    </form>
  </div>
</div>
<?php admin_footer(); ?>
