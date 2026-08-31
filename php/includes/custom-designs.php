<?php
/**
 * Özel Üretim (Custom Made) — sabit tasarım kataloğu + tılsım sembolleri.
 * DB gerektirmez; bu dosya düzenlenip FTP ile yüklenerek güncellenir.
 */

if (!function_exists('custom_config')) {
    function custom_config(): array {
        return [
            'price_usd'    => 2250,
            'discount_pct' => 10,          // siteden havale ile ödeyene her gün
            'lead_time'    => '3 hafta',
            'phone'        => '905323091383', // Özel Üretim WhatsApp hattı
            'free_ship'    => true,
        ];
    }
}
if (!function_exists('custom_price_txt')) {
    function custom_price_txt(): string {
        $c = custom_config();
        return number_format($c['price_usd'], 0, ',', '.') . ' $';
    }
}
if (!function_exists('custom_price_havale_txt')) {
    function custom_price_havale_txt(): string {
        $c = custom_config();
        $v = round($c['price_usd'] * (100 - $c['discount_pct']) / 100);
        return number_format($v, 0, ',', '.') . ' $';
    }
}

if (!function_exists('talisman_svg')) {
    /** Tılsımın minimal çizgi-sembolü (viewBox 48). class ile boyutlandırılır. */
    function talisman_svg(string $no, string $class = 'tsym'): string {
        $p = [
            '01' => '<path d="M31 9a17 17 0 1 0 0 30 13 13 0 1 1 0-30z"/>',
            '02' => '<path d="M5 24c5.5-8.5 32.5-8.5 38 0-5.5 8.5-32.5 8.5-38 0z"/><circle cx="24" cy="24" r="5.5"/><circle cx="24" cy="24" r="1.6" fill="currentColor" stroke="none"/>',
            '03' => '<path d="M25 5c0 6 8 9 8 18a11 11 0 1 1-22 0c0-5 3-8 6-11 0 3 1.5 4.5 3 5-1-6-1-9 5-12z"/>',
            '04' => '<path d="M24 9 41 35H7z"/><path d="M24 35v7M24 42l-6 4M24 42l6 4"/>',
            '05' => '<path d="M24 4 28 20 44 24 28 28 24 44 20 28 4 24 20 20z"/>',
            '06' => '<path d="M6 19q6-7 12 0t12 0t12 0"/><path d="M6 28q6-7 12 0t12 0t12 0"/><path d="M6 37q6-7 12 0t12 0t12 0"/>',
            '07' => '<path d="M10 10h9M29 10h9M10 38h9M29 38h9M10 10v9M10 29v9M38 10v9M38 29v9"/><circle cx="24" cy="24" r="2" fill="currentColor" stroke="none"/>',
            '08' => '<path d="M24 40c-4-7-4-15 0-22 4 7 4 15 0 22z"/><path d="M24 40c-7-1-12-6-14-13 7 0 12 4 14 10M24 40c7-1 12-6 14-13-7 0-12 4-14 10"/>',
        ];
        $no = substr(preg_replace('/\D/', '', $no), -2);
        $g  = $p[$no] ?? '';
        return '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $g . '</svg>';
    }
}

if (!function_exists('custom_talismans')) {
    /** Müşterinin seçip yerleştirebileceği 8 tılsım (şekilli). */
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
if (!function_exists('custom_talisman_by_no')) {
    function custom_talisman_by_no(string $no) {
        $key = 'Nº ' . str_pad(preg_replace('/\D/', '', $no), 2, '0', STR_PAD_LEFT);
        foreach (custom_talismans() as $t) if ($t['no'] === $key) return $t;
        return null;
    }
}

if (!function_exists('custom_designs')) {
    /** 12 özel tasarım. charm = eşlik eden tılsım numarası. */
    function custom_designs(): array {
        $d = [
            [
                'slug' => 'buz-hazinesi', 'no' => 'Nº C-01', 'name' => 'Buz Hazinesi', 'charm' => '06',
                'teaser' => 'Kırağı tutmuş bir kristal mağarasının serinliği.',
                'story'  => 'Kırağının tuttuğu bir kristal mağarasına adım atıyorsunuz; ametist morları, buz mavileri ve donmuş halatlar birbirine sarılmış. Bu parça, dışarıdaki soğuğa inat içeride sıcak kalmayı bilenler için. Ayazın ortasında bile ışıldayan bir sabrı, kırılmadan berraklaşan bir gücü taşır. Onu giydiğinizde, en zorlu günlerde bile içinizdeki durgun suyun hâlâ pırıl pırıl olduğunu hatırlarsınız. Tek nüsha; tıpkı hiçbir kar tanesinin bir diğerine benzememesi gibi.',
            ],
            [
                'slug' => 'firtina-sonrasi', 'no' => 'Nº C-02', 'name' => 'Fırtına Sonrası', 'charm' => '05',
                'teaser' => 'Karanlığın ardından doğan yanardöner ışık.',
                'story'  => 'Gökyüzü karardıktan, gök gürledikten sonra gelen o an: her şeyin yanardöner bir ışıkla yıkandığı, renklerin petrol yeşilinden mora savrulduğu sessizlik. Bu tasarım, zorlukların ardından gelen güzelliğe inananlar için. Karanlığı görmüş ama parlamaktan vazgeçmemiş bir ruhun rengi. Üstünüze aldığınızda, atlattığınız her fırtınanın sizi nasıl ışıldattığını fısıldar. Yalnızca sizin için dokunmuş, tek bir nüsha.',
            ],
            [
                'slug' => 'nehir-taslari', 'no' => 'Nº C-03', 'name' => 'Nehir Taşları', 'charm' => '04',
                'teaser' => 'Suyun yıllarca yonttuğu taşların dinginliği.',
                'story'  => 'Bir nehrin yıllarca sabırla yonttuğu taşlar, boyalı ipeklere ve yosuna sarılmış; acele etmeden güzelleşen her şeyin dokusu. Bu parça, gösterişten çok derinliği sevenler için. Toprağa yakın, sakin, gerçek. Onu giyen, zamanın en güzel süsleyici olduğunu bilir. Doğanın imzası gibi; dünyada bir benzeri daha yok.',
            ],
            [
                'slug' => 'kristal-bahce', 'no' => 'Nº C-04', 'name' => 'Kristal Bahçe', 'charm' => '08',
                'teaser' => 'Değerli taşların açtığı bir bahçe.',
                'story'  => 'Pembe kuvars, sitrin ve dağ kristalinin altın halatlar arasında açtığı bir mücevher bahçesi. Bolluğu ve zarafeti aynı anda taşıyan, göz kamaştıran ama asla abartıya kaçmayan bir tasarım. Bu parça, hayattan güzelliği esirgemeyenler için. Üstünüzde bir bahçe taşırsınız; her taş bir dilek, her renk bir bereket. Yalnızca sizin için toplanmış, tek nüsha bir hazine.',
            ],
            [
                'slug' => 'ranin-nefesi', 'no' => 'Nº C-05', 'name' => 'Ra’nın Nefesi', 'charm' => '02',
                'teaser' => 'Güneş tanrısının altın mitolojisi.',
                'story'  => 'Şahin başlı Horus, ateşten güneş diski, piramitler ve lapis mavisi… Atölye RA’nın adını aldığı güneşin nefesi bu parçada yaşıyor. Antik bir gücü, doğuştan gelen bir asaleti sırtınıza alırsınız. Onu giyen, kendini bir hükümdar kadar sağlam, bir tapınak kadar korunaklı hisseder. Binlerce yıllık bir duayı üstünüzde taşırsınız — üstelik yeryüzünde tek bir tane.',
            ],
            [
                'slug' => 'zafer', 'no' => 'Nº C-06', 'name' => 'Zafer', 'charm' => '03',
                'teaser' => 'Işığın patlayarak kutladığı an.',
                'story'  => 'Işığın patlayarak kutladığı an: gökkuşağı kristali, kalp biçimli bir pırlanta ve dört bir yana savrulan renkler. Bu tasarım, kazandığı günü sonuna kadar yaşamak isteyenler için. Uzun bir yolun sonundaki o coşkuyu, o “başardım” hissini üstünüze giydirir. Onu giyen mütevazı değil, muzafferdir. Zaferiniz kadar biricik; tek nüsha.',
            ],
            [
                'slug' => 'kitsune', 'no' => 'Nº C-07', 'name' => 'Kitsune', 'charm' => '06',
                'teaser' => 'Japon masalından bir tilki ruhu.',
                'story'  => 'Beyaz tilki maskesi, origami ejder, büyük dalga ve akçaağaç yaprakları… 夢幻泡影 — rüya, hayal, köpük ve gölge. Japon masallarından süzülüp gelen gizemli, zarif bir düş. Bu parça, sıradanlığa sığmayan, biraz muzip biraz büyülü ruhlar için. Onu giyen, kalabalıkta bile kendi masalını yaşar. Bir rüya kadar kişisel; dünyada eşi yok.',
            ],
            [
                'slug' => 'sakura-gecesi', 'no' => 'Nº C-08', 'name' => 'Sakura Gecesi', 'charm' => '01',
                'teaser' => 'Kiraz çiçekleri altında bir tiyatro.',
                'story'  => 'Nō ve kabuki maskeleri, kızıl torii kapısı, altın koi ve dökülen kiraz çiçekleri… Japon gecesinin hem dramı hem inceliği. Bu tasarım, güçlü ve kırılgan yanını aynı anda taşımaktan korkmayanlar için. Sahneye çıkan bir sırrı, söylenmemiş bir şiiri andırır. Onu giyen, bakışları üzerine çeker ama gizemini korur. Bir gece kadar derin; tek nüsha.',
            ],
            [
                'slug' => 'girdap', 'no' => 'Nº C-09', 'name' => 'Girdap', 'charm' => '07',
                'teaser' => 'İpeğe dönüşmüş bir yıldızlı deniz.',
                'story'  => 'Turkuaz ve lacivert dalgalar, inci taneleri ve mercan kızılı bir iplik, merkeze doğru dönüp duran bir girdap. Van Gogh’un yıldızlı gecesinin kumaştaki karşılığı; hipnotize eden, akıp giden bir hareket. Bu parça, hayatın akışına güvenenler için. Onu giyen, kaosun bile bir düzeni olduğunu bilir. Suyun hafızası gibi; hiçbir dalga bir öncekine benzemez — tıpkı bu tek nüsha gibi.',
            ],
            [
                'slug' => 'ilk-bahar', 'no' => 'Nº C-10', 'name' => 'İlk Bahar', 'charm' => '08',
                'teaser' => 'En yumuşak pastellerden bir sabah.',
                'story'  => 'En yumuşak pasteller: pudra pembesi, bebek mavisi ve krem; işlemeli çiçekler, inciler ve usulca dalgalanan şifon. Bir ilkbahar sabahının masumiyetini ve umudunu taşır. Bu tasarım, inceliği ve zarafeti bir yaşam biçimi sayanlar için. Onu giyen, yeniden başlamanın hep mümkün olduğunu hatırlar. Bir çiçeğin açması kadar nazik; yalnızca sizin için, tek nüsha.',
            ],
            [
                'slug' => 'sonbahar-sarayi', 'no' => 'Nº C-11', 'name' => 'Sonbahar Sarayı', 'charm' => '07',
                'teaser' => 'Bereketin baroque zenginliği.',
                'story'  => 'Petrol yeşili, mor ve turuncunun altın işlemeyle buluştuğu görkemli bir doku; kadife, olgun meyveler ve bereket. Bir sarayın sonbahar salonu kadar zengin, sıcak ve doyurucu. Bu parça, hayatın olgun güzelliğini kutlayanlar için. Onu giyen, yıllarını bir taç gibi taşır. Hasadın bolluğu gibi cömert; dünyada tek bir tane.',
            ],
            [
                'slug' => 'alacakaranlik', 'no' => 'Nº C-12', 'name' => 'Alacakaranlık', 'charm' => '01',
                'teaser' => 'Gün batımıyla gecenin birleştiği renk.',
                'story'  => 'Mor, bakır ve lacivertin süzüldüğü, işlemeli çiçekler ve toprak tonlarıyla dokunmuş asil bir tasarım. Günün bittiği, gecenin henüz başlamadığı o büyülü aralık. Bu parça, iki dünya arasında zarafetle durmayı bilenler için. Onu giyen, hem gündüzün sıcaklığını hem gecenin gizemini taşır. Gün batımı gibi kısa ve eşsiz bir an — tek nüsha, tıpkı siz gibi.',
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
