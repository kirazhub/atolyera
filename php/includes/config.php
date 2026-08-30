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
        'mersis'     => '',                        // (opsiyonel) MERSİS no — e-Devlet'ten; boşsa yasal sayfada gösterilmez
        'address'    => 'Mithatpaşa Mah. Bahçeköy Cad. No: 55 B17 İç Kapı No: 1, Eyüpsultan / İstanbul',
        'phone'      => '+90 532 309 31 83',       // müşteri iletişim telefonu
        'email'      => 'art@atolyera.com',
        'kep'        => '',                        // SANA KALAN (opsiyonel): KEP adresi
    ],

    // --- Sipariş bildirimi ---
    'order_notify_email' => 'art@atolyera.com',   // yeni sipariş sana buraya düşer

    // --- WhatsApp ---
    'whatsapp' => [
        'contact_phone'    => '905323093183',       // müşterilerin ulaşacağı numara (wa.me linki)
        'contact_enabled'  => true,                 // sitede WhatsApp butonu göster
        'contact_text'     => 'Merhaba Atölye RA 🌿 Bana nasıl bir sanat eseri giydirebilirsiniz? Öneri almak istiyorum.', // link tıklanınca hazır gelen mesaj
        // Otomatik bildirim (CallMeBot ile — ücretsiz):
        'notify_enabled'   => false,                // apikey girince true yap
        'notify_phone'     => '905323093183',       // siparişin düşeceği numaran
        'callmebot_apikey' => '{{CALLMEBOT_APIKEY}}', // SANA KALAN: bkz. KURULUM.txt (tek seferlik onay)
    ],

    // --- SMTP (e-posta gönderimi) — GOOGLE WORKSPACE ---
    // art@atolyera.com Google Workspace'te. Normal şifre DEĞİL, "Uygulama Şifresi" (App Password) gerekir.
    'smtp' => [
        'enabled'   => false,                     // App Password girince true yap
        'host'      => 'smtp.gmail.com',          // Google SMTP
        'port'      => 587,                        // 587 = TLS
        'secure'    => 'tls',
        'username'  => 'art@atolyera.com',
        'password'  => '{{GOOGLE_APP_PASSWORD}}',  // SANA KALAN: 16 haneli Uygulama Şifresi
        'from'      => 'art@atolyera.com',
        'from_name' => 'Atölye RA',
    ],

    // --- Yönetim paneli ---
    // Kullanıcı adı burada; şifre veritabanında saklanır.
    // Varsayılan şifre: atolyera-2026  (panele girince "Şifre Değiştir"den değiştir)
    'admin' => [
        'user' => 'admin',
    ],

    // --- Ödeme ---
    'payment' => [
        // Kredi kartı (iyzico) — TL olarak tahsil edilir
        'card_enabled'  => false,                      // anahtarları girince true yap
        'sandbox'       => true,                        // TEST için true; gerçek satışa geçince false yap
        'iyzico_api'    => '{{IYZICO_API_KEY}}',        // SANA KALAN: iyzico API Key (test veya canlı)
        'iyzico_secret' => '{{IYZICO_SECRET_KEY}}',     // SANA KALAN: iyzico Secret Key
        'iyzico_base'   => 'https://api.iyzipay.com',   // (sandbox=true iken otomatik sandbox adresi kullanılır)
        // Havale / EFT
        'bank_enabled'  => true,
    ],

    // --- Para birimleri (TL bazlı; USD/EUR günlük TCMB kuruyla çevrilir) ---
    'bank_account_name' => 'REKA YAZILIM TURİZM TİC. VE SAN. A.Ş.',
    'currencies' => [
        'TRY' => ['symbol' => '₺', 'label' => 'TL',  'iban' => 'TR48 0006 2000 4790 0006 2937 32'],
        'USD' => ['symbol' => '$', 'label' => 'USD', 'iban' => 'TR47 0006 2000 4790 0009 0551 46'],
        'EUR' => ['symbol' => '€', 'label' => 'EUR', 'iban' => 'TR74 0006 2000 4790 0009 0551 45'],
    ],

    // --- Satış noktaları / butikler (yerinde görüp almak isteyenler için) ---
    // Yeni butik eklemek için diziye satır ekle. maps: Google Haritalar bağlantısı.
    'stores' => [
        [
            'name'    => 'Beymen İstanbul',
            'address' => '{{BEYMEN ŞUBE ADRESİ}}',      // SANA KALAN: şube adresi
            'maps'    => '',                             // SANA KALAN: Google Haritalar linki
            'note'    => 'Atölye RA parçaları Beymen İstanbul’da.',
        ],
        // Örnek ek butik (doldurup çoğaltabilirsin):
        // [ 'name' => 'Butik Adı', 'address' => 'Tam adres', 'maps' => 'https://maps.google.com/...', 'note' => '' ],
    ],
];
