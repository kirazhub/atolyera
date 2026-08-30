<?php
/** Döviz kuru (TCMB günlük) + para birimi seçimi */

/** TL bazlı kurları döndürür: ['USD'=>TL_per_USD, 'EUR'=>TL_per_EUR] | null */
function fx_rates(): ?array {
    static $cache = null;
    if ($cache !== null) return $cache ?: null;

    $file = __DIR__ . '/../data/fx.json';
    $today = date('Y-m-d');

    // Bugünün önbelleği var mı?
    if (is_file($file)) {
        $j = json_decode(@file_get_contents($file), true);
        if (is_array($j) && ($j['date'] ?? '') === $today && !empty($j['rates']['USD'])) {
            return $cache = $j['rates'];
        }
    }

    // TCMB'den çek
    $rates = null;
    try {
        $ch = curl_init('https://www.tcmb.gov.tr/kurlar/today.xml');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>6,
            CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_USERAGENT=>'AtolyeRA/1.0']);
        $body = curl_exec($ch); curl_close($ch);
        if ($body) {
            $xml = @simplexml_load_string($body);
            if ($xml) {
                $r = [];
                foreach ($xml->Currency as $c) {
                    $code = (string)$c['CurrencyCode'];
                    if (in_array($code, ['USD','EUR'], true)) {
                        $v = (float)str_replace(',', '.', (string)$c->ForexSelling);
                        if ($v > 0) $r[$code] = $v;
                    }
                }
                if (!empty($r['USD']) && !empty($r['EUR'])) $rates = $r;
            }
        }
    } catch (\Throwable $e) { error_log('[fx] '.$e->getMessage()); }

    if ($rates) {
        @file_put_contents($file, json_encode(['date'=>$today,'rates'=>$rates]));
        return $cache = $rates;
    }
    // Çekilemezse: bayat önbelleği kullan
    if (isset($j) && !empty($j['rates']['USD'])) return $cache = $j['rates'];
    $cache = false; return null;
}

function fx_available(): bool { return fx_rates() !== null; }

/** Seçili para birimi (oturum) */
function current_currency(): string {
    $allowed = array_keys(config('currencies', ['TRY'=>[]]));
    $c = $_SESSION['cur'] ?? 'TRY';
    if (!in_array($c, $allowed, true)) return 'TRY';
    if ($c !== 'TRY' && !fx_available()) return 'TRY';
    return $c;
}

/** TL tutarını hedef para birimine çevirir */
function fx_convert(float $tl, ?string $cur = null): float {
    $cur = $cur ?: current_currency();
    if ($cur === 'TRY') return $tl;
    $r = fx_rates();
    if (!$r || empty($r[$cur])) return $tl;
    return $tl / $r[$cur];
}

/** Belirli para birimi için IBAN */
function currency_iban(string $cur): string {
    return config("currencies.$cur.iban", config('currencies.TRY.iban', ''));
}
