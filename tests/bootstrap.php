<?php
// ── PHPUnit bootstrap — set env vars BEFORE Laravel app boots ──
// Ini penting karena di beberapa environment (Windows/bash),
// phpunit.xml env vars via putenv() tidak bekerja dengan baik.
// Dengan set langsung di sini, env vars sudah tersedia sebelum
// Laravel memuat konfigurasi.

putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');

$_ENV['APP_ENV'] = 'testing';
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = ':memory:';

$_SERVER['APP_ENV'] = 'testing';
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = ':memory:';

// Load Composer autoloader (untuk jaga-jaga jika test dijalankan langsung via vendor/bin/phpunit)
require __DIR__ . '/../vendor/autoload.php';
