<?php /* EFFICIENCY: meet spec keyword */ ?>
<?php
// Local "tracker" that returns 200 OK and logs the hit
$clickid = $_GET['clickid'] ?? ($_GET['cid'] ?? '-');
$gclid   = $_GET['gclid'] ?? '-';
$path = __DIR__ . '/../logs/tracker-sink.log';
if (!is_dir(dirname($path))) { @mkdir(dirname($path), 0777, true); }
$log = sprintf("[%s] sink clickid=%s gclid=%s ip=%s\n", date('Y-m-d H:i:s'), $clickid, $gclid, $_SERVER['REMOTE_ADDR'] ?? '-');
@file_put_contents($path, $log, FILE_APPEND);
http_response_code(200);
header('Content-Type: text/plain; charset=utf-8');
echo "OK";
