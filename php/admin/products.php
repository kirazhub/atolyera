<?php
/** Admin ürün listesi */
require_once __DIR__ . '/_auth.php';
admin_require();

// hızlı işlemler: aktiflik / silme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $id = (int)($_POST['id'] ?? 0);
    if (($_POST['do'] ?? '') === 'toggle') {
        db()->prepare('UPDATE products SET is_active = 1 - is_active WHERE id=?')->execute([$id]);
        flash_set('info','Ürün görünürlüğü değişti.');
    } elseif (($_POST['do'] ?? '') === 'delete') {
        db()->prepare('DELETE FROM products WHERE id=?')->execute([$id]);
        flash_set('info','Ürün silindi.');
    }
    redirect('/admin/products.php');
}

$rows = db()->query('SELECT * FROM products ORDER BY sort')->fetchAll();
admin_header('Ürünler');
?>
<div style="display:flex;align-items:center;justify-content:space-between;">
  <h1 style="margin:0;">Ürünler (<?= count($rows) ?>)</h1>
  <a href="<?= url('/admin/product-edit.php') ?>" class="btn btn--solid">+ Yeni Ürün</a>
</div>
<table class="admin-table" style="margin-top:20px;">
  <thead><tr><th></th><th>Ürün</th><th>Kategori</th><th>Fiyat</th><th>Durum</th><th style="width:210px;">İşlem</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $p): ?>
    <tr>
      <td><img src="<?= asset('images/'.$p['image']) ?>" alt=""></td>
      <td><strong><?= e($p['name']) ?></strong><br><span style="color:#999;font-size:12px;"><?= e($p['no_label']) ?></span></td>
      <td><?= e($p['cat_name']) ?></td>
      <td><?= money($p['price']) ?></td>
      <td><span class="pill <?= $p['is_active']?'pill--hazir':'pill--iptal' ?>"><?= $p['is_active']?'Görünür':'Gizli' ?></span></td>
      <td>
        <a href="<?= url('/admin/product-edit.php?id='.(int)$p['id']) ?>" class="btn btn--ghost btn--sm">Düzenle</a>
        <form method="post" style="display:inline;"><?= csrf_field() ?><input type="hidden" name="do" value="toggle"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn--ghost btn--sm"><?= $p['is_active']?'Gizle':'Göster' ?></button></form>
        <form method="post" style="display:inline;" onsubmit="return confirm('Bu ürün silinsin mi?');"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn--ghost btn--sm" style="color:var(--bordeaux);border-color:var(--bordeaux);">Sil</button></form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php admin_footer(); ?>
