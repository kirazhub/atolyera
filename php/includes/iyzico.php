<?php
/** iyzico kredi kartı ödemesi (Checkout Form) */

/** iyzipay kütüphanesini gerektiğinde yükler; yoksa false döner */
function iyzico_boot(): bool {
    static $ok = null;
    if ($ok !== null) return $ok;
    $bs = __DIR__ . '/../lib/iyzipay/IyzipayBootstrap.php';
    if (!is_file($bs)) return $ok = false;
    require_once $bs;
    IyzipayBootstrap::init(__DIR__ . '/../lib/iyzipay/src');
    return $ok = true;
}

/** Kart ödemesi kullanılabilir mi? (kütüphane var, anahtarlar girildi ve açık) */
function iyzico_enabled(): bool {
    if (!config('payment.card_enabled')) return false;
    $api = (string)config('payment.iyzico_api');
    $sec = (string)config('payment.iyzico_secret');
    if ($api === '' || $sec === '' || strpos($api, '{{') !== false || strpos($sec, '{{') !== false) return false;
    return iyzico_boot();
}

function iyzico_options(): \Iyzipay\Options {
    $o = new \Iyzipay\Options();
    $o->setApiKey(config('payment.iyzico_api'));
    $o->setSecretKey(config('payment.iyzico_secret'));
    $o->setBaseUrl(config('payment.iyzico_base', 'https://api.iyzipay.com'));
    return $o;
}

/**
 * Checkout Form başlatır. Başarılıysa ödeme sayfası URL'sini döner.
 * @return array{ok:bool,url?:string,token?:string,error?:string}
 */
function iyzico_init_checkout(array $order, array $items): array {
    try {
        $req = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $req->setLocale(\Iyzipay\Model\Locale::TR);
        $req->setConversationId($order['order_code']);
        $price = number_format((float)$order['subtotal'], 2, '.', '');
        $paid  = number_format((float)$order['total'], 2, '.', '');
        $req->setPrice($price);
        $req->setPaidPrice($paid);
        $req->setCurrency(\Iyzipay\Model\Currency::TL);
        $req->setBasketId($order['order_code']);
        $req->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $req->setCallbackUrl(site_url('iyzico-callback.php'));

        // Alıcı
        $nameParts = preg_split('/\s+/', trim($order['customer_name']), 2);
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId('BUYER-' . $order['id']);
        $buyer->setName($nameParts[0] ?? $order['customer_name']);
        $buyer->setSurname($nameParts[1] ?? '-');
        $buyer->setGsmNumber($order['phone'] ?: '+900000000000');
        $buyer->setEmail($order['email']);
        $buyer->setIdentityNumber('11111111111');
        $buyer->setRegistrationAddress($order['address'] ?: $order['city']);
        $buyer->setIp($_SERVER['REMOTE_ADDR'] ?? '85.34.78.112');
        $buyer->setCity($order['city'] ?: 'İstanbul');
        $buyer->setCountry('Turkey');
        $req->setBuyer($buyer);

        $addr = new \Iyzipay\Model\Address();
        $addr->setContactName($order['customer_name']);
        $addr->setCity($order['city'] ?: 'İstanbul');
        $addr->setCountry('Turkey');
        $addr->setAddress($order['address'] ?: '-');
        $req->setShippingAddress($addr);
        $req->setBillingAddress($addr);

        // Sepet kalemleri (toplamları price'a eşit olmalı)
        $basket = [];
        foreach ($items as $it) {
            $bi = new \Iyzipay\Model\BasketItem();
            $bi->setId('P' . $it['id']);
            $bi->setName($it['name']);
            $bi->setCategory1($it['cat_name'] ?: 'Kimono');
            $bi->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $bi->setPrice(number_format((float)$it['line_total'], 2, '.', ''));
            $basket[] = $bi;
        }
        // Kargo ücreti varsa ek kalem (price = kalemler toplamı olmalı)
        $ship = (int)config('shipping_fee', 0);
        if ($ship > 0) {
            $bi = new \Iyzipay\Model\BasketItem();
            $bi->setId('SHIP'); $bi->setName('Kargo'); $bi->setCategory1('Kargo');
            $bi->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $bi->setPrice(number_format((float)$ship, 2, '.', ''));
            $basket[] = $bi;
            $req->setPrice(number_format((float)$order['total'], 2, '.', ''));
        }
        $req->setBasketItems($basket);

        $init = \Iyzipay\Model\CheckoutFormInitialize::create($req, iyzico_options());
        if ($init->getStatus() === 'success') {
            return ['ok' => true, 'url' => $init->getPaymentPageUrl(), 'token' => $init->getToken()];
        }
        error_log('[iyzico] init hata: ' . $init->getErrorMessage());
        return ['ok' => false, 'error' => $init->getErrorMessage() ?: 'Ödeme başlatılamadı.'];
    } catch (\Throwable $ex) {
        error_log('[iyzico] init exception: ' . $ex->getMessage());
        return ['ok' => false, 'error' => 'Ödeme servisine ulaşılamadı.'];
    }
}

/**
 * Ödeme sonucunu doğrular.
 * @return array{ok:bool,paid:bool,paymentId?:string,error?:string}
 */
function iyzico_retrieve(string $token): array {
    try {
        $req = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $req->setLocale(\Iyzipay\Model\Locale::TR);
        $req->setToken($token);
        $res = \Iyzipay\Model\CheckoutForm::retrieve($req, iyzico_options());
        $paid = ($res->getStatus() === 'success' && $res->getPaymentStatus() === 'SUCCESS');
        return ['ok' => $res->getStatus() === 'success', 'paid' => $paid,
                'paymentId' => $res->getPaymentId(), 'error' => $res->getErrorMessage()];
    } catch (\Throwable $ex) {
        error_log('[iyzico] retrieve exception: ' . $ex->getMessage());
        return ['ok' => false, 'paid' => false, 'error' => 'Ödeme doğrulanamadı.'];
    }
}
