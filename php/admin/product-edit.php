<?php
/** Admin ürün ekle/düzenle */
require_once __DIR__ . '/_auth.php';
admin_require();

function admin_slugify(string $s): string {
    $tr = ['ı'=>'i','İ'=>'i','ğ'=>'g','Ğ'=>'g','ü'=>'u','Ü'=>'u','ş'=>'s','Ş'=>'s','ö'=>'o','Ö'=>'o','ç'=>'c','Ç'=>'c'];
    $s = mb_strtolower(strtr($s, $tr), 'UTF-8');
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim($s, '-');
}

$id = (int)($_GET['id'] ?? 0);
$p = $id ? product_by_id($id) : null;
$cats = categories();

// varsayılan boş kayıt
$d = $p ?: ['id'=>0,'slug'=>'','no_label'=>'','name'=>'','story'=>'','muse'=>'','charm_label'=>'✦ Tılsım',
    'charm'=>'','fabric_note'=>'','material'=>'','price'=>71000,'size'=>'Tek beden (free size)',
    'image'=>'','image_webp'=>'','width'=>1000,'height'=>1500,'cat_slug'=>'baski','cat_name'=>'','badge'=>'','sort'=>999,'is_active'=>1,'is_sold'=>0];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) $errors[] = 'Oturum doğrulanamadı.';
    foreach (['no_label','name','story','muse','charm_label','charm','fabric_note','material','badge','image'] as $k)
        $d[$k] = trim($_POST[$k] ?? '');
    $d['price']  = (int)($_POST['price'] ?? 0);
    $d['size']   = trim($_POST['size'] ?? 'Tek beden (free size)');
    $d['width']  = (int)($_POST['width'] ?? 1000);
    $d['height'] = (int)($_POST['height'] ?? 1500);
    $d['sort']   = (int)($_POST['sort'] ?? 999);
    $d['is_active'] = isset($_POST['is_active']) ? 1 : 0;
    $d['is_sold'] = isset($_POST['is_sold']) ? 1 : 0;
    $d['cat_slug'] = preg_replace('/[^a-z0-9-]/','',$_POST['cat_slug'] ?? 'baski');
    $catRow = category_by_slug($d['cat_slug']);
    $d['cat_name'] = $catRow['name'] ?? '';

    if ($d['name'] === '') $errors[] = 'Ürün adı gerekli.';
    if ($d['price'] <= 0)  $errors[] = 'Geçerli bir fiyat girin.';

    // Görsel yükleme (opsiyonel)
    if (!empty($_FILES['image_file']['tmp_name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
        $tmp = $_FILES['image_file']['tmp_name'];
        $info = @getimagesize($tmp);
        if (!$info) { $errors[] = 'Yüklenen dosya geçerli bir görsel değil.'; }
        else {
            $base = admin_slugify($d['name'] ?: ('urun-'.time()));
            $dst  = __DIR__ . '/../images/' . $base . '.jpg';
            $ok = false;
            switch ($info[2]) {
                case IMAGETYPE_JPEG: $img = @imagecreatefromjpeg($tmp); break;
                case IMAGETYPE_PNG:  $img = @imagecreatefrompng($tmp);  break;
                case IMAGETYPE_WEBP: $img = @imagecreatefromwebp($tmp); break;
                default: $img = null;
            }
            if ($img) {
                imagejpeg($img, $dst, 82);
                if (function_exists('imagewebp')) imagewebp($img, __DIR__ . '/../images/' . $base . '.webp', 80);
                imagedestroy($img);
                $d['image'] = $base . '.jpg';
                $d['image_webp'] = file_exists(__DIR__.'/../images/'.$base.'.webp') ? $base.'.webp' : $base.'.jpg';
                $d['width'] = $info[0]; $d['height'] = $info[1];
                $ok = true;
            }
            if (!$ok && !$img) $errors[] = 'Görsel işlenemedi.';
        }
    }
    if ($d['image'] === '') $errors[] = 'Bir görsel yükleyin veya görsel dosya adı girin.';
    if ($d['image'] && $d['image_webp'] === '') $d['image_webp'] = preg_replace('/\.jpg$/','.webp',$d['image']);

    if (!$errors) {
        // slug
        $slug = $p['slug'] ?? admin_slugify($d['name']);
        if (!$p) { $bs=$slug; $i=2; while (product_by_slug($slug)) { $slug = $bs.'-'.$i++; } }
        $d['slug'] = $slug;

        if ($p) {
            $sql='UPDATE products SET slug=:slug,no_label=:no_label,name=:name,story=:story,muse=:muse,
                charm_label=:charm_label,charm=:charm,fabric_note=:fabric_note,material=:material,price=:price,
                size=:size,image=:image,image_webp=:image_webp,width=:width,height=:height,cat_slug=:cat_slug,
                cat_name=:cat_name,badge=:badge,sort=:sort,is_active=:is_active,is_sold=:is_sold WHERE id=:id';
        } else {
            $sql='INSERT INTO products (slug,no_label,name,story,muse,charm_label,charm,fabric_note,material,price,
                size,image,image_webp,width,height,cat_slug,cat_name,badge,sort,is_active,is_sold)
                VALUES (:slug,:no_label,:name,:story,:muse,:charm_label,:charm,:fabric_note,:material,:price,
                :size,:image,:image_webp,:width,:height,:cat_slug,:cat_name,:badge,:sort,:is_active,:is_sold)';
        }
        $st = db()->prepare($sql);
        $params = [];
        foreach (['slug','no_label','name','story','muse','charm_label','charm','fabric_note','material','price',
            'size','image','image_webp','width','height','cat_slug','cat_name','badge','sort','is_active','is_sold'] as $k) $params[':'.$k]=$d[$k];
        if ($p) $params[':id']=$p['id'];
        $st->execute($params);
        flash_set('success', $p ? 'Ürün güncellendi.' : 'Ürün eklendi.');
        redirect('/admin/products.php');
    }
}

admin_header($p ? 'Ürün Düzenle' : 'Yeni Ürün');
?>
<p><a href="<?= url('/admin/products.php') ?>" style="font-family:var(--sans);font-size:12px;color:var(--graphite-soft);">← Ürünler</a></p>
<h1><?= $p ? 'Ürün Düzenle' : 'Yeni Ürün' ?></h1>
<?php foreach ($errors as $er): ?><div class="flash flash--error" style="margin-bottom:12px;"><?= e($er) ?></div><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" style="max-width:820px;background:#fff;border:1px solid var(--line);padding:28px;">
  <?= csrf_field() ?>
  <div class="form-grid">
    <div class="field"><label>Ürün Adı</label><input name="name" value="<?= e($d['name']) ?>" required></div>
    <div class="field"><label>No / Etiket (Nº I)</label><input name="no_label" value="<?= e($d['no_label']) ?>"></div>
    <div class="field"><label>Fiyat (₺)</label><input type="number" name="price" value="<?= (int)$d['price'] ?>" required></div>
    <div class="field"><label>Beden</label><input name="size" value="<?= e($d['size']) ?>"></div>
    <div class="field"><label>Kategori</label>
      <select name="cat_slug" style="padding:12px;border:1px solid var(--line);font-family:var(--serif-text);">
        <?php foreach ($cats as $c): ?><option value="<?= e($c['slug']) ?>" <?= $d['cat_slug']===$c['slug']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="field"><label>Rozet (Baskılı/Sınırlı…)</label><input name="badge" value="<?= e($d['badge']) ?>"></div>
    <div class="field"><label>Kumaş (spec)</label><input name="material" value="<?= e($d['material']) ?>"></div>
    <div class="field"><label>Sıra (küçük = önce)</label><input type="number" name="sort" value="<?= (int)$d['sort'] ?>"></div>
    <div class="field field--full"><label>Hikâye</label><textarea name="story"><?= e($d['story']) ?></textarea></div>
    <div class="field field--full"><label>İlham (muse, italik)</label><textarea name="muse"><?= e($d['muse']) ?></textarea></div>
    <div class="field"><label>Tılsım Başlığı</label><input name="charm_label" value="<?= e($d['charm_label']) ?>"></div>
    <div class="field"><label>Tılsım Metni</label><input name="charm" value="<?= e($d['charm']) ?>"></div>
    <div class="field field--full"><label>Kumaş Notu (alt)</label><textarea name="fabric_note"><?= e($d['fabric_note']) ?></textarea></div>

    <div class="field field--full">
      <label>Görsel</label>
      <?php if ($d['image']): ?><img src="<?= asset('images/'.$d['image']) ?>" alt="" style="width:120px;height:auto;margin-bottom:8px;"><?php endif; ?>
      <input type="file" name="image_file" accept="image/*">
      <span style="font-family:var(--sans);font-size:12px;color:#999;">veya mevcut dosya adı:</span>
      <input name="image" value="<?= e($d['image']) ?>" placeholder="orn: 1.jpg">
    </div>
    <div class="field"><label>Görsel genişlik (px)</label><input type="number" name="width" value="<?= (int)$d['width'] ?>"></div>
    <div class="field"><label>Görsel yükseklik (px)</label><input type="number" name="height" value="<?= (int)$d['height'] ?>"></div>
    <div class="field field--full"><label class="check"><input type="checkbox" name="is_active" value="1" <?= $d['is_active']?'checked':'' ?>> <span>Sitede görünür</span></label>
      <label class="check" style="margin-top:8px;"><input type="checkbox" name="is_sold" value="1" <?= !empty($d['is_sold'])?'checked':'' ?>> <span>Sahibini Buldu (satıldı — sepete eklenemez)</span></label></div>
  </div>
  <button class="btn btn--solid" style="margin-top:20px;"><?= $p?'Kaydet':'Ekle' ?></button>
</form>
<?php admin_footer(); ?>
