<?php
// Insert a new lead + optional tracker postback
require_once __DIR__ . '/../config.php';

$name  = trim($_POST['name']  ?? '');
$email = trim($_POST['email'] ?? '');

// Capture gclid/sub_id from POST, then GET (forwarded), then cookies
$gclid = trim($_POST['gclid'] ?? $_GET['gclid'] ?? $_COOKIE['gclid'] ?? '');
$subId = trim($_POST['sub_id'] ?? $_GET['sub_id'] ?? $_COOKIE['sub_id'] ?? '');

// Optional: DEMO fallbacks so values are never empty (remove for prod)
if ($gclid === '') $gclid = 'G-DEMO-' . time();
if ($subId === '') $subId = 'S-DEMO-' . time();

if ($name === '' || $email === '') {
  http_response_code(422); echo "Name and Email are required."; exit;
}

$sql = "INSERT INTO leads (name, email, gclid, sub_id) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $gclid, $subId]);

// Optional: fire tracker postback (server-side) + log result
$logFile = __DIR__ . '/../logs/postback.log';
if (!is_dir(dirname($logFile))) { @mkdir(dirname($logFile), 0777, true); }

if (!empty($TRACKER_POSTBACK_URL)) {
  $pbUrl = str_replace(['{sub_id}','{gclid}'], [rawurlencode($subId), rawurlencode($gclid)], $TRACKER_POSTBACK_URL);
  $status = null; $body = '';
  try {
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $pbUrl,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 5,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HEADER => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => 0,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
  } catch (Throwable $e) {
    $status = 0; $body = $e->getMessage();
  }
  $line = sprintf("[%s] %s status=%s body=%s\n", date('Y-m-d H:i:s'), $pbUrl, (string)$status, substr(preg_replace('/\s+/', ' ', (string)$body), 0, 200));
  @file_put_contents($logFile, $line, FILE_APPEND);
}

header("Location: admin.php?created=1"); exit;
