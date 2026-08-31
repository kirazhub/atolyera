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

    // --- Satıcı / yasal bilgiler (BİREYSEL — Raif Kurt) ---
    'company' => [
        'title'      => 'Raif Kurt',
        'brand'      => 'Atölye RA',
        'tax_office' => '',                        // bireysel — şahıs işletmesi vergi no varsa buraya
        'tax_no'     => '',                        // (şahıs işletmesi vergi no / yoksa boş)
        'mersis'     => '',
        'address'    => 'Mithatpaşa Mah. Bahçeköy Cad. No: 55 B17 İç Kapı No: 1, Eyüpsultan / İstanbul', // SANA KALAN: bireysel adresini doğrula
        'phone'      => '+90 532 309 13 83',
        'email'      => 'art@atolyera.com',
        'kep'        => '',
    ],

    // --- Sipariş bildirimi ---
    'order_notify_email' => 'art@atolyera.com',   // yeni sipariş sana buraya düşer

    // --- WhatsApp ---
    'whatsapp' => [
        'contact_phone'    => '905323091383',       // müşterilerin ulaşacağı numara (wa.me linki)
        'contact_enabled'  => true,                 // sitede WhatsApp butonu göster
        'contact_text'     => 'Merhaba Atölye RA 🌿 Bana nasıl bir sanat eseri giydirebilirsiniz? Öneri almak istiyorum.', // link tıklanınca hazır gelen mesaj
        // Otomatik bildirim (CallMeBot ile — ücretsiz):
        'notify_enabled'   => false,                // apikey girince true yap
        'notify_phone'     => '905323091383',       // siparişin düşeceği numaran
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
    // Giriş: kullanıcı adı RA · şifre veritabanında (varsayılan 1234568)
    'admin' => [
        'user' => 'RA',
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

    // --- Shopier (kredi kartı — iyzico alternatifi) ---
    'shopier' => [
        'enabled'       => false,                       // anahtarları girince true yap
        'api_key'       => '{{SHOPIER_API_KEY}}',       // SANA KALAN: Shopier API Kullanıcı/Key
        'api_secret'    => '{{SHOPIER_API_SECRET}}',    // SANA KALAN: Shopier API Şifre/Secret
        'website_index' => 1,                            // Shopier'da tek site ise 1
    ],

    // --- Para birimleri (TL bazlı; USD/EUR günlük TCMB kuruyla çevrilir) ---
    // Havale hesabı bireysel (Raif Kurt) — sadece TL. Döviz siparişlerde TL karşılığı bu hesaba yatar.
    'bank_account_name' => 'Raif Kurt',
    'currencies' => [
        'TRY' => ['symbol' => '₺', 'label' => 'TL',  'iban' => 'TR62 0006 2000 4790 0006 6656 69'],
        'USD' => ['symbol' => '$', 'label' => 'USD', 'iban' => 'TR62 0006 2000 4790 0006 6656 69'],
        'EUR' => ['symbol' => '€', 'label' => 'EUR', 'iban' => 'TR62 0006 2000 4790 0006 6656 69'],
    ],

    // --- Satış noktaları / butikler (yerinde görüp almak isteyenler için) ---
    // Beymen mağazaları + (belli olunca) butikler. maps: Google Haritalar bağlantısı.
    'stores' => [
        [ 'name' => 'Beymen Nişantaşı', 'address' => 'Abdi İpekçi Cad., Nişantaşı — Şişli / İstanbul',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+Ni%C5%9Fanta%C5%9F%C4%B1', 'note' => 'Atölye RA parçalarını burada deneyebilirsiniz.' ],
        [ 'name' => 'Beymen Zorlu Center', 'address' => 'Zorlu Center, Levazım — Beşiktaş / İstanbul',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+Zorlu+Center', 'note' => '' ],
        [ 'name' => 'Beymen İstinye Park', 'address' => 'İstinye Park AVM — Sarıyer / İstanbul',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+%C4%B0stinye+Park', 'note' => '' ],
        [ 'name' => 'Beymen Akmerkez', 'address' => 'Akmerkez AVM, Etiler — Beşiktaş / İstanbul',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+Akmerkez', 'note' => '' ],
        [ 'name' => 'Beymen Suadiye', 'address' => 'Bağdat Cad., Suadiye — Kadıköy / İstanbul',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+Suadiye', 'note' => '' ],
        [ 'name' => 'Diğer Beymen Mağazaları', 'address' => 'Türkiye genelindeki tüm Beymen mağazaları.',
          'maps' => 'https://www.google.com/maps/search/?api=1&query=Beymen+ma%C4%9Faza', 'note' => 'Haritadan size en yakın Beymen’i bulun.' ],
        [ 'name' => 'Seçkin Butikler', 'address' => 'Yakında — seçkin butiklerde. Adresler ve konumlar eklenecek.',
          'maps' => '', 'note' => 'Yakında' ],
    ],
];
