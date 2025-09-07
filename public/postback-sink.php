<?php
// local sink — simulate tracker
$logFile = __DIR__ . '/../logs/tracker-sink.log';
if (!is_dir(dirname($logFile))) mkdir(dirname($logFile),0777,true);

$clickid = $_GET['clickid'] ?? '';
$gclid   = $_GET['gclid'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$line = sprintf("[%s] sink clickid=%s gclid=%s ip=%s\n",
  date('Y-m-d H:i:s'),$clickid,$gclid,$ip);
file_put_contents($logFile,$line,FILE_APPEND);

http_response_code(200);
echo "OK";
