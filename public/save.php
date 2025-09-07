<?php /* EFFICIENCY */ ?>
<?php
require_once __DIR__ . '/../config.php';

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$gclid = trim($_POST['gclid'] ?? '');
$subId = trim($_POST['sub_id'] ?? '');

if ($name === '' || $email === '') {
  http_response_code(422);
  echo "Name and Email are required.";
  exit;
}

// Insert lead
$stmt = $pdo->prepare("INSERT INTO leads (name,email,gclid,sub_id) VALUES (?,?,?,?)");
$stmt->execute([$name,$email,$gclid,$subId]);

// Fire postback
$logFile = __DIR__ . '/../logs/postback.log';
if (!is_dir(dirname($logFile))) mkdir(dirname($logFile),0777,true);

if (!empty($TRACKER_POSTBACK_URL)) {
  $params = http_build_query(['clickid'=>$subId,'gclid'=>$gclid]);
  $pbUrl = $TRACKER_POSTBACK_URL . '?' . $params;

  $ch = curl_init();
  curl_setopt_array($ch,[
    CURLOPT_URL=>$pbUrl,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_TIMEOUT=>5
  ]);
  $body=curl_exec($ch);
  $status=curl_getinfo($ch,CURLINFO_HTTP_CODE);
  curl_close($ch);

  $line = sprintf("[%s] %s status=%s body=%s\n",
    date('Y-m-d H:i:s'),$pbUrl,$status,$body ?: '');
  file_put_contents($logFile,$line,FILE_APPEND);
}

header("Location: admin.php?created=1");
exit;
