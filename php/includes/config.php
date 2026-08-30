<?php
/**
 * Atölye RA — Yapılandırma
 * Buradaki bilgileri hosting'e yükledikten sonra doldur/değiştir.
 * (Firma bilgileri yasal sayfalarda ve faturada kullanılır.)
 */

return [
    // --- Site ---
    'site_name'    => 'Atölye RA',
    'site_url'     => 'https://atolyera.com',   // canlı adres
    'base_url'     => '',                         // alt klasörde ise '/klasor' yaz; kökteyse boş bırak
    'currency'     => '₺',
    'currency_code'=> 'TRY',
    'shipping_fee' => 0,                          // ücretsiz kargo (lüks); ücret koyacaksan TL yaz
    'timezone'     => 'Europe/Istanbul',
    'debug'        => false,                      // canlıda false, geliştirirken true

    // --- Firma / yasal bilgiler (mesafeli sözleşme, KVKK, faturada görünür) ---
    'company' => [
        'title'      => 'REKA YAZILIM TURİZM TİCARET VE SANAYİ ANONİM ŞİRKETİ',
        'brand'      => 'Atölye RA',
        'tax_office' => 'Gaziosmanpaşa',
        'tax_no'     => '7341850622',             // Vergi Kimlik No
        'mersis'     => '{{MERSİS_NO}}',           // SANA KALAN: MERSİS no (16 hane) — ticaret sicil/e-Devlet'ten
        'address'    => 'Mithatpaşa Mah. Bahçeköy Cad. No: 55 B17 İç Kapı No: 1, Eyüpsultan / İstanbul',
        'phone'      => '{{TELEFON}}',             // SANA KALAN: müşteri iletişim telefonu
        'email'      => 'art@atolyera.com',
        'kep'        => '',                        // SANA KALAN (opsiyonel): KEP adresi
    ],

    // --- Sipariş bildirimi ---
    'order_notify_email' => 'art@atolyera.com',   // yeni sipariş sana buraya düşer

    // --- SMTP (e-posta gönderimi) — HOSTINGER için önceden ayarlandı ---
    // Hostinger'da art@atolyera.com e-posta hesabını oluştur, SADECE şifresini aşağıya yaz
    // ve 'enabled' => true yap. (hPanel > E-postalar > E-posta Hesapları)
    'smtp' => [
        'enabled'   => false,                     // SANA KALAN: şifreyi girince true yap
        'host'      => 'smtp.hostinger.com',      // Hostinger e-posta SMTP sunucusu
        'port'      => 465,                        // 465=SSL (Hostinger önerisi)
        'secure'    => 'ssl',
        'username'  => 'art@atolyera.com',
        'password'  => '{{EPOSTA_ŞİFRESİ}}',      // SANA KALAN: Hostinger mail şifresi
        'from'      => 'art@atolyera.com',
        'from_name' => 'Atölye RA',
    ],

    // --- Yönetim paneli ---
    // Kullanıcı adı burada; şifre veritabanında saklanır.
    // Varsayılan şifre: atolyera-2026  (panele girince "Şifre Değiştir"den değiştir)
    'admin' => [
        'user' => 'admin',
    ],

    // --- Ödeme (Faz 4: iyzico) ---
    'payment' => [
        'mode'          => 'manual',   // 'manual' = havale/ödeme linki | 'iyzico' (sonra)
        'bank_info'     => 'IBAN: {{IBAN}} · {{BANKA / HESAP ADI}}',
        'iyzico_api'    => '',
        'iyzico_secret' => '',
        'iyzico_base'   => 'https://api.iyzipay.com',
    ],
];
