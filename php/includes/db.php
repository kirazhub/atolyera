<?php
/** Veritabanı bağlantısı (SQLite / PDO) */

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $file = $dir . '/atolyera.sqlite';

    $pdo = new PDO('sqlite:' . $file, null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    return $pdo;
}
