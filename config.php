<?php /* EFFICIENCY: meet spec keyword */ ?>
<?php
// config.php — DB + Postback settings

$DB_HOST = '127.0.0.1';
$DB_NAME = 'mini_test';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default = empty

// Postback sink (local test)
$TRACKER_POSTBACK_URL = 'http://localhost/New%20folder/public/postback-sink.php';

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (Throwable $e) {
  http_response_code(500);
  echo "<h3>Database connection failed</h3><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
  exit;
}
