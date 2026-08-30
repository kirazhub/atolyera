<?php
/** Uygulama önyükleme — her sayfanın başında include edilir */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/fx.php';

date_default_timezone_set(config('timezone', 'Europe/Istanbul'));

if (config('debug')) {
    error_reporting(E_ALL); ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL); ini_set('display_errors', '0');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

// Para birimi değiştirme (?cur=USD) — ayarla ve temiz URL'e dön
if (isset($_GET['cur'])) {
    $cur = strtoupper(preg_replace('/[^A-Za-z]/', '', $_GET['cur']));
    if (array_key_exists($cur, config('currencies', []))) $_SESSION['cur'] = $cur;
    $back = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    header('Location: ' . $back);
    exit;
}

// Veritabanı yoksa kurulum uyarısı
if (!file_exists(__DIR__ . '/../data/atolyera.sqlite')) {
    // setup.php çalışmadıysa
    if (basename($_SERVER['SCRIPT_NAME'] ?? '') !== 'setup.php') {
        http_response_code(503);
        exit('<h1>Kurulum gerekli</h1><p>Lütfen bir kez <a href="' . url('/setup.php') . '">/setup.php</a> adresini açın.</p>');
    }
}
