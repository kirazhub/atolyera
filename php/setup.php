<?php
/**
 * Kurulum — bir kez çalıştır: tabloları oluşturur, 32 ürünü ve kategorileri yükler.
 * Tarayıcıdan /setup.php aç. Zaten kuruluysa güvenle tekrar çalıştırılabilir (verileri korur).
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: text/html; charset=utf-8');
$pdo = db();

$pdo->exec('
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,
    intro TEXT DEFAULT "",
    sort INTEGER DEFAULT 0
);
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    no_label TEXT DEFAULT "",
    name TEXT NOT NULL,
    story TEXT DEFAULT "",
    muse TEXT DEFAULT "",
    charm_label TEXT DEFAULT "",
    charm TEXT DEFAULT "",
    fabric_note TEXT DEFAULT "",
    material TEXT DEFAULT "",
    price INTEGER NOT NULL DEFAULT 0,
    size TEXT DEFAULT "Tek beden (free size)",
    image TEXT DEFAULT "",
    image_webp TEXT DEFAULT "",
    width INTEGER DEFAULT 1000,
    height INTEGER DEFAULT 1500,
    cat_slug TEXT DEFAULT "baski",
    cat_name TEXT DEFAULT "",
    badge TEXT DEFAULT "",
    stock INTEGER DEFAULT 1,
    is_active INTEGER DEFAULT 1,
    is_sold INTEGER DEFAULT 0,
    sort INTEGER DEFAULT 0
);
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_code TEXT UNIQUE NOT NULL,
    created_at TEXT NOT NULL,
    customer_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT DEFAULT "",
    address TEXT DEFAULT "",
    city TEXT DEFAULT "",
    note TEXT DEFAULT "",
    subtotal INTEGER DEFAULT 0,
    shipping INTEGER DEFAULT 0,
    total INTEGER DEFAULT 0,
    status TEXT DEFAULT "yeni",
    kvkk INTEGER DEFAULT 0,
    contract INTEGER DEFAULT 0,
    mail_sent INTEGER DEFAULT 0,
    payment_method TEXT DEFAULT "bank",
    payment_status TEXT DEFAULT "bekliyor",
    iyzico_token TEXT DEFAULT "",
    iyzico_payment_id TEXT DEFAULT "",
    display_currency TEXT DEFAULT "TRY",
    fx_rate REAL DEFAULT 1,
    display_total REAL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER,
    name TEXT NOT NULL,
    no_label TEXT DEFAULT "",
    price INTEGER NOT NULL,
    qty INTEGER NOT NULL,
    line_total INTEGER NOT NULL,
    FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT
);
');

$log = [];

/* --- Mevcut DB'ye eksik kolonları ekle (güvenli migrasyon) --- */
$cols = array_column($pdo->query('PRAGMA table_info(orders)')->fetchAll(), 'name');
$add = [
    'payment_method'    => 'TEXT DEFAULT "bank"',
    'payment_status'    => 'TEXT DEFAULT "bekliyor"',
    'iyzico_token'      => 'TEXT DEFAULT ""',
    'iyzico_payment_id' => 'TEXT DEFAULT ""',
    'display_currency'  => 'TEXT DEFAULT "TRY"',
    'fx_rate'           => 'REAL DEFAULT 1',
    'display_total'     => 'REAL DEFAULT 0',
];
foreach ($add as $c => $def) {
    if (!in_array($c, $cols, true)) { $pdo->exec("ALTER TABLE orders ADD COLUMN $c $def"); $log[] = "orders.$c kolonu eklendi."; }
}

/* --- Kategoriler --- */
$cats = [
    ['baski','Sanat Baskı Koleksiyonu','İpek saten, viskon, viskon şifon ve saten üzerine hazırlanan sanat baskısı kimonolar.',1],
    ['tilsim','Tılsım Koleksiyonu','Sade bir elbisenin üzerine atılan, TENCEL üzerine tılsım nakışlı örtüler.',2],
    ['seri','Tılsım Serisi','Her biri bir tılsım taşıyan, sınırlı sayıda TENCEL nakışlı parçalar.',3],
    ['mon','Özel Parça','Yalnızca saten ipek üzerine, imzalı ve tek.',4],
];
$cst = $pdo->prepare('INSERT INTO categories(slug,name,intro,sort) VALUES(?,?,?,?)
    ON CONFLICT(slug) DO UPDATE SET name=excluded.name, intro=excluded.intro, sort=excluded.sort');
foreach ($cats as $c) $cst->execute($c);
$log[] = count($cats) . ' kategori yüklendi.';

/* --- Ürünler (seed.json) --- */
$seed = json_decode(file_get_contents(__DIR__ . '/seed.json'), true);
$pst = $pdo->prepare('INSERT INTO products
    (slug,no_label,name,story,muse,charm_label,charm,fabric_note,material,price,size,image,image_webp,width,height,cat_slug,cat_name,badge,stock,is_active,sort)
    VALUES (:slug,:no_label,:name,:story,:muse,:charm_label,:charm,:fabric_note,:material,:price,:size,:image,:image_webp,:width,:height,:cat_slug,:cat_name,:badge,1,1,:sort)
    ON CONFLICT(slug) DO UPDATE SET
    no_label=excluded.no_label,name=excluded.name,story=excluded.story,muse=excluded.muse,
    charm_label=excluded.charm_label,charm=excluded.charm,fabric_note=excluded.fabric_note,
    material=excluded.material,price=excluded.price,size=excluded.size,image=excluded.image,
    image_webp=excluded.image_webp,width=excluded.width,height=excluded.height,
    cat_slug=excluded.cat_slug,cat_name=excluded.cat_name,badge=excluded.badge,sort=excluded.sort');
$n = 0;
foreach ($seed as $p) {
    $pst->execute([
        ':slug'=>$p['slug'], ':no_label'=>$p['no_label'], ':name'=>$p['name'],
        ':story'=>$p['story'], ':muse'=>$p['muse'], ':charm_label'=>$p['charm_label'],
        ':charm'=>$p['charm'], ':fabric_note'=>$p['fabric_note'], ':material'=>$p['material'],
        ':price'=>$p['price'], ':size'=>$p['size'], ':image'=>$p['image'],
        ':image_webp'=>$p['image_webp'], ':width'=>$p['width'], ':height'=>$p['height'],
        ':cat_slug'=>$p['cat_slug'], ':cat_name'=>$p['cat_name'], ':badge'=>$p['badge'], ':sort'=>$p['sort'],
    ]); $n++;
}
$log[] = "$n ürün yüklendi.";

/* --- Admin şifresi (varsayılan: atolyera-2026) --- */
$has = $pdo->query("SELECT value FROM settings WHERE key='admin_pass'")->fetchColumn();
if (!$has) {
    $pdo->prepare("INSERT INTO settings(key,value) VALUES('admin_pass',?)")
        ->execute([password_hash('atolyera-2026', PASSWORD_DEFAULT)]);
    $log[] = 'Admin şifresi ayarlandı (varsayılan: atolyera-2026 — panelden değiştirin).';
}

echo '<div style="font-family:system-ui;max-width:640px;margin:40px auto;line-height:1.7;">';
echo '<h1>Atölye RA — Kurulum tamam ✓</h1><ul>';
foreach ($log as $l) echo '<li>' . htmlspecialchars($l) . '</li>';
echo '</ul><p><a href="' . url('/') . '">Anasayfaya git</a> · <a href="' . url('/admin/') . '">Yönetim paneli</a></p>';
echo '<p style="color:#a00;"><strong>Güvenlik:</strong> Kurulum bittiğinde bu <code>setup.php</code> dosyasını sunucudan silin.</p>';
echo '</div>';
