<?php
/**
 * Sana Özel Tasarım (Custom Made) — sabit tasarım kataloğu.
 * DB gerektirmez; bu dosya düzenlenip FTP ile yüklenerek güncellenir.
 *
 * Ortak koşullar (config yerine burada, tek yerden değişsin):
 *   - price_usd : 2250  (sabit dolar fiyatı)
 *   - lead_time : "3 hafta"
 *   - base      : "standart bayan kimono kalıbı, kişiye özel ölçü"
 */

if (!function_exists('custom_config')) {
    function custom_config(): array {
        return [
            'price_usd' => 2250,
            'lead_time' => '3 hafta',
        ];
    }
}

if (!function_exists('custom_talismans')) {
    /** Müşterinin seçip yerleştirebileceği 8 tılsım. */
    function custom_talismans(): array {
        return [
            ['no' => 'Nº 01', 'name' => 'Denge Hilali',  'meaning' => 'Zıtlıkları uzlaştırır; hayatınıza uyum ve iç denge taşısın diye.'],
            ['no' => 'Nº 02', 'name' => 'Koruyan Göz',   'meaning' => 'Nazarı savuşturan bakış; sizi görünmeyenden kollasın diye.'],
            ['no' => 'Nº 03', 'name' => 'Ejderin Nefesi','meaning' => 'İçinizdeki gücü ve cesareti uyandıran ateş; korkuya yer kalmasın diye.'],
            ['no' => 'Nº 04', 'name' => 'Toprak Ayı',    'meaning' => 'Köklenmenin duası; sarsıntıda bile sağlam ve dengede kalasınız diye.'],
            ['no' => 'Nº 05', 'name' => 'Yön Yıldızı',   'meaning' => 'Karanlıkta yol gösteren pusula; yolunuzu hiç şaşırmayasınız diye.'],
            ['no' => 'Nº 06', 'name' => 'Sisin Sırrı',   'meaning' => 'Sezginin fısıltısı; iç sesinizi duyup gizli olanı göresiniz diye.'],
            ['no' => 'Nº 07', 'name' => 'Dört Kapı',     'meaning' => 'Evrenin düzenini taşıyan koruma; her yönden güvende olasınız diye.'],
            ['no' => 'Nº 08', 'name' => 'Lotus Doğuşu',  'meaning' => 'Her yeni başlangıcın arınması; sudan tertemiz doğasınız diye.'],
        ];
    }
}

if (!function_exists('custom_designs')) {
    /** 12 özel tasarım. image: images/custom/custom-XX.(jpg|webp) */
    function custom_designs(): array {
        $d = [
            [
                'slug' => 'buz-hazinesi', 'no' => 'Nº C-01', 'name' => 'Buz Hazinesi',
                'teaser' => 'Kırağı tutmuş bir kristal mağarasının serinliği.',
                'story'  => 'Ametist ve buz mavisi kristallerin, kırağıyla kaplı iplerin ve gece lacivertine boyanmış kadifenin buluştuğu bir hazine. Ayazın içinde saklanan sıcak bir sabır gibi; soğukta bile ışıldayan bir sükûnet taşır.',
            ],
            [
                'slug' => 'firtina-sonrasi', 'no' => 'Nº C-02', 'name' => 'Fırtına Sonrası',
                'teaser' => 'Karanlığın ardından doğan yanardöner ışık.',
                'story'  => 'Gecenin siyahına düşen yağ-üstü-su renkleri; petrol yeşili, mor ve altın bir arada dalgalanır. Her fırtınanın ardından gelen o yanardöner aydınlığın giyilebilir hâli — zorluktan sonra parlamayı hatırlatır.',
            ],
            [
                'slug' => 'nehir-taslari', 'no' => 'Nº C-03', 'name' => 'Nehir Taşları',
                'teaser' => 'Suyun yıllarca yonttuğu taşların dinginliği.',
                'story'  => 'Boyalı ipeklerle sarılmış nehir taşları, yosun ve inci; toprağın sabırla biriktirdiği bir doku. Zamanın acele etmeden güzelleştirdiği her şeyin hikâyesi; sakin, köklü, gerçek.',
            ],
            [
                'slug' => 'kristal-bahce', 'no' => 'Nº C-04', 'name' => 'Kristal Bahçe',
                'teaser' => 'Değerli taşların açtığı bir bahçe.',
                'story'  => 'Pembe kuvars, sitrin ve dağ kristali; altın halatlar ve ametist geodun içinde açan bir mücevher bahçesi. Bolluğun ve inceliğin bir arada durduğu, göz kamaştıran ama zarafetini hiç kaybetmeyen bir parça.',
            ],
            [
                'slug' => 'ranin-nefesi', 'no' => 'Nº C-05', 'name' => 'Ra’nın Nefesi',
                'teaser' => 'Güneş tanrısının altın mitolojisi.',
                'story'  => 'Şahin başlı Horus, ateşten güneş diski, piramitler, ankh ve lapis mavisi… Atölye RA’nın adını aldığı güneşin nefesi. Antik bir gücü sırtına alan, doğduğu andan beri kudretli hissettiren bir tasarım.',
            ],
            [
                'slug' => 'zafer', 'no' => 'Nº C-06', 'name' => 'Zafer',
                'teaser' => 'Işığın patlayarak kutladığı an.',
                'story'  => 'Gökkuşağı kristali, kalp biçimli pırlanta ve savrulan renkler bir zafer çığlığı gibi patlar. Kazanılmış bir günün, uzun bir yolun sonundaki o coşkulu ışıltıyı üstünüze giydiren neşeli bir parça.',
            ],
            [
                'slug' => 'kitsune', 'no' => 'Nº C-07', 'name' => 'Kitsune',
                'teaser' => 'Japon masalından bir tilki ruhu.',
                'story'  => 'Beyaz tilki maskesi (kitsune), origami ejder, büyük dalga ve akçaağaç yaprakları… “夢幻泡影” — rüya, hayal, köpük, gölge. Gizemli ve zarif; masalların içinden çıkıp gelmiş bir düş gibi.',
            ],
            [
                'slug' => 'sakura-gecesi', 'no' => 'Nº C-08', 'name' => 'Sakura Gecesi',
                'teaser' => 'Kiraz çiçekleri altında bir tiyatro.',
                'story'  => 'Nō ve kabuki maskeleri, kızıl torii kapısı, altın koi ve kiraz çiçekleri… Japon gecesinin dramı ve inceliği bir arada. Sahneye çıkan bir sırrı taşır; hem güçlü hem kırılgan.',
            ],
            [
                'slug' => 'girdap', 'no' => 'Nº C-09', 'name' => 'Girdap',
                'teaser' => 'İpeğe dönüşmüş bir yıldızlı deniz.',
                'story'  => 'Turkuaz ve lacivert dalgalar, inci taneleri ve mercan kızılı bir iplik girdabın merkezine doğru akar. Van Gogh’un yıldızlı gecesinin kumaştaki karşılığı; durmadan dönen, hipnotize eden bir akış.',
            ],
            [
                'slug' => 'ilk-bahar', 'no' => 'Nº C-10', 'name' => 'İlk Bahar',
                'teaser' => 'En yumuşak pastellerden bir sabah.',
                'story'  => 'Pudra pembesi, bebek mavisi ve krem; işlemeli çiçekler, inciler ve şifon bir ilkbahar sabahı gibi açar. Nazik, romantik, zamansız — kendini kutlayan bir zarafet.',
            ],
            [
                'slug' => 'sonbahar-sarayi', 'no' => 'Nº C-11', 'name' => 'Sonbahar Sarayı',
                'teaser' => 'Bereketin baroque zenginliği.',
                'story'  => 'Petrol yeşili, mor ve turuncunun altın işlemeyle buluştuğu görkemli bir doku; kadife, meyveler ve bereket. Bir sarayın sonbahar salonu kadar zengin, sıcak ve doyurucu.',
            ],
            [
                'slug' => 'alacakaranlik', 'no' => 'Nº C-12', 'name' => 'Alacakaranlık',
                'teaser' => 'Gün batımıyla gecenin birleştiği renk.',
                'story'  => 'Mor, bakır ve lacivertin süzüldüğü, işlemeli çiçekler ve toprak tonlarıyla dokunmuş asil bir tasarım. Günün bittiği, gecenin henüz başlamadığı o büyülü aralığı üstünüzde taşır.',
            ],
        ];
        foreach ($d as &$x) {
            $i = (int)preg_replace('/\D/', '', substr($x['no'], -2));
            $x['img']      = sprintf('images/custom/custom-%02d.jpg', $i);
            $x['img_webp'] = sprintf('images/custom/custom-%02d.webp', $i);
        }
        return $d;
    }
}

if (!function_exists('custom_by_slug')) {
    function custom_by_slug(string $slug) {
        foreach (custom_designs() as $d) if ($d['slug'] === $slug) return $d;
        return null;
    }
}
