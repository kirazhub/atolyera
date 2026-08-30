<?php
/** Yardımcı fonksiyonlar */

/* --- Config erişimi --- */
function config($key = null, $default = null) {
    static $cfg = null;
    if ($cfg === null) $cfg = require __DIR__ . '/config.php';
    if ($key === null) return $cfg;
    // nokta ile derin erişim: config('smtp.host')
    $val = $cfg;
    foreach (explode('.', $key) as $k) {
        if (is_array($val) && array_key_exists($k, $val)) $val = $val[$k];
        else return $default;
    }
    return $val;
}

/* --- Güvenli çıktı --- */
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* --- URL/asset --- */
function base_url(): string { return rtrim((string)config('base_url', ''), '/'); }
function url(string $path = ''): string {
    $path = '/' . ltrim($path, '/');
    return base_url() . ($path === '/' ? '/' : $path);
}
function asset(string $path): string { return url($path); }
function site_url(string $path = ''): string { return rtrim(config('site_url'), '/') . '/' . ltrim($path, '/'); }

/* --- Para biçimi (para birimine duyarlı; TL bazlı) --- */
function money($amount, ?string $cur = null): string {
    $cur = $cur ?: (function_exists('current_currency') ? current_currency() : 'TRY');
    $curs = config('currencies', ['TRY' => ['symbol' => '₺']]);
    if (!isset($curs[$cur])) $cur = 'TRY';
    $sym = $curs[$cur]['symbol'] ?? '₺';
    if ($cur === 'TRY' || !function_exists('fx_convert')) {
        return number_format((float)$amount, 0, ',', '.') . ' ' . $sym;
    }
    $v = fx_convert((float)$amount, $cur);
    return $sym . number_format($v, 0, '.', ',');
}

/* --- Yönlendirme --- */
function redirect(string $path) {
    header('Location: ' . (preg_match('~^https?://~', $path) ? $path : url($path)));
    exit;
}

/* --- CSRF --- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

/* --- Flash mesaj --- */
function flash_set(string $type, string $msg) { $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg]; }
function flash_all(): array { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }

/* --- Ürün sorguları --- */
function product_by_slug(string $slug) {
    $st = db()->prepare('SELECT * FROM products WHERE slug = ? AND is_active = 1');
    $st->execute([$slug]); return $st->fetch() ?: null;
}
function product_by_id($id) {
    $st = db()->prepare('SELECT * FROM products WHERE id = ?');
    $st->execute([$id]); return $st->fetch() ?: null;
}
function products_all(?string $cat = null): array {
    if ($cat) {
        $st = db()->prepare('SELECT * FROM products WHERE is_active=1 AND cat_slug=? ORDER BY sort');
        $st->execute([$cat]);
    } else {
        $st = db()->query('SELECT * FROM products WHERE is_active=1 ORDER BY sort');
    }
    return $st->fetchAll();
}
/** Arama + filtre + sıralama */
function products_list(array $o = []): array {
    $w = ['is_active=1']; $p = [];
    if (!empty($o['cat']))      { $w[] = 'cat_slug = ?'; $p[] = $o['cat']; }
    if (!empty($o['material'])) { $w[] = 'material LIKE ?'; $p[] = '%' . $o['material'] . '%'; }
    if (!empty($o['q'])) {
        $q = '%' . $o['q'] . '%';
        $w[] = '(name LIKE ? OR material LIKE ? OR story LIKE ? OR no_label LIKE ? OR charm LIKE ?)';
        array_push($p, $q, $q, $q, $q, $q);
    }
    $order = 'sort';
    switch ($o['sort'] ?? '') {
        case 'price_asc':  $order = 'price ASC, sort'; break;
        case 'price_desc': $order = 'price DESC, sort'; break;
        case 'new':        $order = 'id DESC'; break;
    }
    $sql = 'SELECT * FROM products WHERE ' . implode(' AND ', $w) . ' ORDER BY ' . $order;
    $st = db()->prepare($sql); $st->execute($p);
    return $st->fetchAll();
}
function categories(): array {
    return db()->query('SELECT * FROM categories ORDER BY sort')->fetchAll();
}
function category_by_slug(string $slug) {
    $st = db()->prepare('SELECT * FROM categories WHERE slug=?'); $st->execute([$slug]);
    return $st->fetch() ?: null;
}

/* --- Sepet (oturum tabanlı) --- */
function cart(): array { return $_SESSION['cart'] ?? []; }
function cart_count(): int { return array_sum(cart()); }
function cart_add($id, int $qty = 1) {
    $id = (int)$id; if ($id <= 0) return;
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1, $qty);
}
function cart_set($id, int $qty) {
    $id = (int)$id;
    if ($qty <= 0) unset($_SESSION['cart'][$id]);
    else $_SESSION['cart'][$id] = $qty;
}
function cart_remove($id) { unset($_SESSION['cart'][(int)$id]); }
function cart_clear() { unset($_SESSION['cart']); }
function cart_items(): array {
    $items = [];
    foreach (cart() as $id => $qty) {
        $p = product_by_id($id);
        if ($p) { $p['qty'] = (int)$qty; $p['line_total'] = $p['price'] * $qty; $items[] = $p; }
    }
    return $items;
}
function cart_subtotal(): int {
    $t = 0; foreach (cart_items() as $it) $t += $it['line_total']; return $t;
}
function cart_total(): int { return cart_subtotal() + (int)config('shipping_fee', 0); }

/* --- Sipariş kodu --- */
function new_order_code(): string {
    return 'RA-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

/* --- WhatsApp linki --- */
function wa_link(string $phone, string $text = ''): string {
    $phone = preg_replace('/\D/', '', $phone);
    return 'https://wa.me/' . $phone . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}
function wa_contact(?string $text = null): string {
    return wa_link((string)config('whatsapp.contact_phone', ''), $text ?? (string)config('whatsapp.contact_text', ''));
}
