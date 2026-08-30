<?php
/** Admin sipariş listesi */
require_once __DIR__ . '/_auth.php';
admin_require();
$orders = db()->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll();
admin_header('Siparişler');
?>
<h1>Siparişler (<?= count($orders) ?>)</h1>
<?php if ($orders): ?>
<table class="admin-table">
  <thead><tr><th>Sipariş</th><th>Müşteri</th><th>İletişim</th><th>Tutar</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($orders as $o): ?>
    <tr>
      <td><?= e($o['order_code']) ?></td>
      <td><?= e($o['customer_name']) ?></td>
      <td style="font-size:13px;"><?= e($o['email']) ?><br><?= e($o['phone']) ?></td>
      <td><?= money($o['total']) ?></td>
      <td><span class="pill pill--<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
      <td style="font-size:13px;"><?= e(date('d.m.Y H:i', strtotime($o['created_at']))) ?></td>
      <td><a href="<?= url('/admin/order-view.php?id='.(int)$o['id']) ?>" class="btn btn--ghost btn--sm">Aç</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?><p style="font-family:var(--serif-text);">Henüz sipariş yok.</p><?php endif; ?>
<?php admin_footer(); ?>
