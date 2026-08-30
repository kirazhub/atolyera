<?php
/** Shopier kredi kartı ödemesi (API) */

/** Shopier aktif mi? (anahtarlar girildi ve açık) */
function shopier_enabled(): bool {
    if (!config('shopier.enabled')) return false;
    $k = (string)config('shopier.api_key');
    $s = (string)config('shopier.api_secret');
    return $k !== '' && $s !== '' && strpos($k, '{{') === false && strpos($s, '{{') === false;
}

/** Shopier ödeme formu için gizli alanlar (auto-submit) — TL olarak tahsil */
function shopier_form_fields(array $order, array $items): array {
    $api    = config('shopier.api_key');
    $secret = config('shopier.api_secret');
    $rand   = (string)random_int(100000, 999999);
    $orderId= $order['order_code'];
    $total  = number_format((float)$order['total'], 2, '.', '');
    $currency = 0; // 0 = TL

    $parts = preg_split('/\s+/', trim($order['customer_name']), 2);
    $name  = $parts[0] ?? $order['customer_name'];
    $sur   = $parts[1] ?? '-';

    // İmza: base64( hmac_sha256( random_nr + order_id + total + currency , secret ) )
    $signature = base64_encode(hash_hmac('sha256', $rand . $orderId . $total . $currency, $secret, true));

    return [
        'API_key'          => $api,
        'website_index'    => (int)config('shopier.website_index', 1),
        'platform_order_id'=> $orderId,
        'product_name'     => 'Atölye RA — Sipariş ' . $orderId,
        'product_type'     => 1,                    // fiziksel ürün
        'buyer_name'       => $name,
        'buyer_surname'    => $sur,
        'buyer_email'      => $order['email'],
        'buyer_account_age'=> 0,
        'buyer_id_nr'      => (string)($order['id'] ?? '0'),
        'buyer_phone'      => $order['phone'],
        'billing_address'  => $order['address'] ?: '-',
        'billing_city'     => $order['city'] ?: 'İstanbul',
        'billing_country'  => 'Turkey',
        'billing_postcode' => '34000',
        'shipping_address' => $order['address'] ?: '-',
        'shipping_city'    => $order['city'] ?: 'İstanbul',
        'shipping_country' => 'Turkey',
        'shipping_postcode'=> '34000',
        'total_order_value'=> $total,
        'currency'         => $currency,
        'platform'         => 0,
        'is_in_frame'      => 0,
        'current_language' => 0,
        'modul_version'    => 'AtolyeRA-1.0',
        'random_nr'        => $rand,
        'signature'        => $signature,
    ];
}

function shopier_endpoint(): string {
    return 'https://www.shopier.com/ShowProduct/api_pay4.php';
}

/** Shopier geri bildirimini (callback) doğrular */
function shopier_verify_callback(array $post): array {
    $secret  = config('shopier.api_secret');
    $status  = $post['status'] ?? '';
    $orderId = $post['platform_order_id'] ?? '';
    $rand    = $post['random_nr'] ?? '';
    $sig     = $post['signature'] ?? '';
    // İmza: base64( hmac_sha256( random_nr + order_id , secret ) )
    $expected = base64_encode(hash_hmac('sha256', $rand . $orderId, $secret, true));
    $ok = is_string($sig) && $sig !== '' && hash_equals($expected, $sig);
    return [
        'ok'         => $ok,
        'order_id'   => $orderId,
        'paid'       => ($ok && strtolower((string)$status) === 'success'),
        'payment_id' => $post['payment_id'] ?? '',
    ];
}
