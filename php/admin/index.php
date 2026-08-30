<?php
/** Admin özet */
require_once __DIR__ . '/_auth.php';
admin_require();

$pdo = db();
$nOrders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$nNew    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='yeni'")->fetchColumn();
$revenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status!='iptal'")->fetchColumn();
$nProd   = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$recent  = $pdo->query('SELECT * FROM orders ORDER BY id DESC LIMIT 8')->fetchAll();

admin_header('Özet');
?>
<h1>Özet</h1>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:34px;">
  <?php
  $cards = [['Sipariş',$nOrders],['Yeni',$nNew],['Ciro',money($revenue)],['Ürün',$nProd]];
  foreach ($cards as $c): ?>
    <div style="background:#fff;border:1px solid var(--line);padding:22px;">
      <div style="font-family:var(--sans);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--bordeaux);"><?= e($c[0]) ?></div>
      <div style="font-family:var(--serif-display);font-size:30px;color:var(--graphite);margin-top:6px;"><?= is_string($c[1])?e($c[1]):$c[1] ?></div>
    </div>
  <?php endforeach; ?>
</div>

<h1 style="font-size:22px;">Son Siparişler</h1>
<?php if ($recent): ?>
<table class="admin-table">
  <thead><tr><th>Sipariş</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($recent as $o): ?>
    <tr>
      <td><?= e($o['order_code']) ?></td>
      <td><?= e($o['customer_name']) ?></td>
      <td><?= money($o['total']) ?></td>
      <td><span class="pill pill--<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span></td>
      <td><?= e(date('d.m.Y H:i', strtotime($o['created_at']))) ?></td>
      <td><a href="<?= url('/admin/order-view.php?id='.(int)$o['id']) ?>" class="btn btn--ghost btn--sm">Aç</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?><p style="font-family:var(--serif-text);">Henüz sipariş yok.</p><?php endif; ?>
<?php admin_footer(); ?>
