<?php
$logs = [
  __DIR__ . '/../logs/postback.log' => 'Postback Client Log',
  __DIR__ . '/../logs/tracker-sink.log' => 'Local Tracker Sink Log',
];
?><!doctype html>
<html><head><meta charset="utf-8"><title>Logs</title>
<style>
  body{font-family:Arial;margin:2rem;}
  pre{background:#f9f9f9;padding:1rem;border:1px solid #ccc;}
</style></head>
<body>
<h2>Logs</h2>
<?php foreach($logs as $path=>$label): ?>
  <h3><?=htmlspecialchars($label)?></h3>
  <pre><?php
    if(is_file($path)) echo htmlspecialchars(file_get_contents($path));
    else echo "(no entries yet)";
  ?></pre>
<?php endforeach; ?>
</body></html>
